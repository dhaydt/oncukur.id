<?php

namespace App\Http\Controllers\api\v3\mitra;

use App\CPU\BackEndHelper;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\mitra_wallet;
use App\Model\Mitra;
use App\Model\OrderTransaction;
use App\Model\WithdrawRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;

class MitraController extends Controller
{
    public function is_online(Request $request)
    {
        $id = Helpers::get_mitra_by_token($request)['data'];
        if (!$id) {
            return response()->json(['auth-001' => 'Your existing session token does not authorize you any more']);
        }
        // dd($id['id']);
        $mitra = Mitra::with('wallet')->find($id['id']);
        if ($request->online == 1) {
            $saldo = $mitra->wallet->total_earning;
            $minim = Helpers::minimal_online();
            if ($saldo < $minim) {
                return response()->json(['status' => 'failed', 'message' => translate('Your_balance_is_not_sufficient_to_receive_the_order/booking')]);
            }
        }
        $mitra->is_online = $request->online;
        $mitra->save();

        return response()->json(['status' => 'success', 'message' => translate('Successfully_change_online_status')]);
    }

    public function topUp(Request $request)
    {
        $val = $request['nominal'];
        $customer = Helpers::get_mitra_by_token($request)['data'];
        if (!$customer) {
            return response()->json(['auth-001' => 'Your existing session token does not authorize you any more']);
        }
        $value = $val;

        $user = [
                'given_names' => $customer['name'],
                'email' => $customer['email'],
                'mobile_number' => $customer['phone'],
            ];

        // session()->put('transaction_ref', $tran);

        Config::$serverKey = config('midtrans.server_key');

        Config::$clientKey = config('midtrans.client_key');

        // non-relevant function only used for demo/example purpose
        // $this->printExampleWarningMessage();

        // Uncomment for production environment
        Config::$isProduction = false;

        // Enable sanitization
        Config::$isSanitized = true;

        // Enable 3D-Secure
        Config::$is3ds = true;

        $params = [
                'transaction_details' => [
                    'order_id' => $customer['id'].'-'.now(),
                    'gross_amount' => $value,
                ],
                'payment_type' => 'gopay',
                'gopay' => [
                    'enable_callback' => true,                // optional
                    'callback_url' => env('APP_URL').'/mitra/topUp-success'.'/'.$customer['id'].'/'.$val,
                ],
            ];

        try {
            // Get Snap Payment Page URL
            $paymentUrl = \Midtrans\CoreApi::charge($params);

            // Redirect to Snap Payment Page
            // dd($paymentUrl);

            return response()->json(['status' => 'success', 'payment_url' => $paymentUrl->actions[1]->url]);

            return redirect()->away($paymentUrl->actions[1]->url);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function checkDevice(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'your device registered',
        ]);
    }

    public function mitra_info(Request $request)
    {
        $data = Helpers::get_mitra_by_token($request);
        if ($data['success'] == 1) {
            $mitra = $data['data'];
        } else {
            return response()->json([
                'auth-001' => 'Your existing session token does not authorize you any more',
            ], 401);
        }

        return response()->json(Mitra::with('wallet')->withCount('orders')->where(['id' => $mitra['id']])->first());
    }

    public function update(Request $request)
    {
        $data = Helpers::get_mitra_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'status' => 'error',
                'auth-001' => 'Your existing session token does not authorize you any more',
            ], 401);
        }

        $old_image = Mitra::where(['id' => $seller['id']])->first()->image;
        $image = $request->file('image');
        if ($image != null) {
            $imageName = ImageManager::update('mitra/', $old_image, 'png', $request->file('image'));
        } else {
            $imageName = $old_image;
        }

        $old_ktp = Mitra::where(['id' => $seller['id']])->first()->ktp;
        $ktp = $request->file('ktp');
        if ($ktp != null) {
            $ktpName = ImageManager::update('ktp/', $old_ktp, 'png', $request->file('ktp'));
        } else {
            $ktpName = $old_ktp;
        }

        Mitra::where(['id' => $seller['id']])->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'image' => $imageName,
            'ktp' => $ktpName,
            'password' => $request['password'] != null ? bcrypt($request['password']) : Mitra::where(['id' => $seller['id']])->first()->password,
            'updated_at' => now(),
        ]);

        if ($request['password'] != null) {
            Mitra::where(['id' => $seller['id']])->update([
                'auth_token' => Str::random('50'),
            ]);
        }

        return response()->json(Helpers::responseSuccess('Mitra info updated successfully'), 200);
    }

    public function withdraw_request(Request $request)
    {
        $data = Helpers::get_mitra_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $wallet = mitra_wallet::where('mitra_id', $seller['id'])->first();
        if (($wallet->total_earning) >= Convert::usd($request['amount']) && $request['amount'] > 1) {
            DB::table('withdraw_requests')->insert([
                'mitra_id' => $seller['id'],
                'amount' => Convert::usd($request['amount']),
                'transaction_note' => null,
                'approved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $wallet->total_earning -= BackEndHelper::currency_to_usd($request['amount']);
            $wallet->pending_withdraw += BackEndHelper::currency_to_usd($request['amount']);
            $wallet->save();

            return response()->json(['status' => 'success', 'message' => translate('Withdraw request sent successfully!')], 200);
        }

        return response()->json(translate('Invalid withdraw request'), 400);
    }

    public function close_withdraw_request(Request $request)
    {
        $data = Helpers::get_mitra_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $withdraw_request = WithdrawRequest::find($request['id']);
        $wallet = mitra_wallet::where('mitra_id', $seller['id'])->first();

        if (isset($withdraw_request) && $withdraw_request->approved == 0) {
            $wallet->total_earning += BackEndHelper::currency_to_usd($withdraw_request['amount']);
            $wallet->pending_withdraw -= BackEndHelper::currency_to_usd($request['amount']);
            $wallet->save();
            $withdraw_request->delete();

            return response()->json([
                'status' => 'success',
                'message' => translate('Withdraw request has been closed successfully!'),
            ], 200);
        }

        return response()->json(translate('Withdraw request is invalid'), 400);
    }

    public function transaction(Request $request)
    {
        $data = Helpers::get_mitra_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $transaction = WithdrawRequest::where('mitra_id', $seller['id'])->latest()->get();

        return response()->json(['status' => 'success', 'data' => $transaction], 200);
    }

    public function monthly_earning(Request $request)
    {
        $data = Helpers::get_mitra_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');
        $seller_data = '';
        $seller_earnings = OrderTransaction::where([
            'seller_is' => 'seller',
            'mitra_id' => $seller['id'],
            'status' => 'disburse',
        ])->select(
            DB::raw('IFNULL(sum(mitra_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; ++$inc) {
            $default = 0;
            foreach ($seller_earnings as $match) {
                if ($match['month'] == $inc) {
                    $default = $match['sums'];
                }
            }
            $seller_data .= $default.',';
        }

        return response()->json(['status' => 'success', 'message' => 'mitra earning per month', 'data' => $seller_data], 200);
    }

    public function monthly_commission_given(Request $request)
    {
        $data = Helpers::get_mitra_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        $commission_data = '';
        $commission_earnings = OrderTransaction::where([
            'seller_is' => 'seller',
            'mitra_id' => $seller['id'],
            'status' => 'disburse',
        ])->select(
            DB::raw('IFNULL(sum(admin_commission),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; ++$inc) {
            $default = 0;
            foreach ($commission_earnings as $match) {
                if ($match['month'] == $inc) {
                    $default = $match['sums'];
                }
            }
            $commission_data .= $default.',';
        }

        return response()->json(['status' => 'success', 'message' => 'commission given to superadmin per month', 'data' => $commission_data], 200);
    }

    public function monthly_commission_given_outlet(Request $request)
    {
        $data = Helpers::get_mitra_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        $commission_data = '';
        $commission_earnings = OrderTransaction::where([
            'seller_is' => 'seller',
            'mitra_id' => $seller['id'],
            'status' => 'disburse',
        ])->select(
            DB::raw('IFNULL(sum(seller_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; ++$inc) {
            $default = 0;
            foreach ($commission_earnings as $match) {
                if ($match['month'] == $inc) {
                    $default = $match['sums'];
                }
            }
            $commission_data .= $default.',';
        }

        return response()->json(['status' => 'success', 'message' => 'commission given to outlet per month', 'data' => $commission_data], 200);
    }
}
