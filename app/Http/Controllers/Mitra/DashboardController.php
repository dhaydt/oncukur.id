<?php

namespace App\Http\Controllers\Mitra;

use App\CPU\Helpers;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\mitra_wallet;
use App\mitra_wallet_histories;
use App\Model\Mitra;
use App\Model\Order;
use App\Model\OrderTransaction;
use App\Model\Product;
use App\Model\Shop;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;

class DashboardController extends Controller
{
    public function index()
    {
        Toastr::info('Welcome to ONCUKUR Dashboard Mitra!');

        $from = Carbon::now()->startOfYear()->format('Y-m-d');

        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        $seller_data = [];
        $seller_earnings = OrderTransaction::where([
            'seller_is' => 'seller',
            'seller_id' => auth('seller')->id(),
            'status' => 'disburse',
        ])->select(
            DB::raw('IFNULL(sum(seller_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; ++$inc) {
            $seller_data[$inc] = 0;
            foreach ($seller_earnings as $match) {
                if ($match['month'] == $inc) {
                    $seller_data[$inc] = $match['sums'];
                }
            }
        }

        $commission_data = [];
        $commission_given = OrderTransaction::where([
            'seller_is' => 'seller',
            'seller_id' => auth('seller')->id(),
            'status' => 'disburse',
        ])->select(
            DB::raw('IFNULL(sum(admin_commission),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; ++$inc) {
            $commission_data[$inc] = 0;
            foreach ($commission_given as $match) {
                if ($match['month'] == $inc) {
                    $commission_data[$inc] = $match['sums'];
                }
            }
        }

        $data = self::order_stats_data();

        $data['customer'] = User::count();
        $data['store'] = Shop::count();
        $data['product'] = Product::count();
        $data['order'] = Order::count();

        // $data['top_sell'] = $top_sell;
        // $data['most_rated_products'] = $most_rated_products;

        $admin_wallet = mitra_wallet::where('mitra_id', auth('mitra')->id())->first();
        $data['total_earning'] = $admin_wallet->total_earning ?? 0;
        $data['withdrawn'] = $admin_wallet->withdrawn ?? 0;
        $data['commission_given'] = $admin_wallet->commission_given ?? 0;
        $data['outlet_commission_given'] = $admin_wallet->outlet_commission_given ?? 0;
        $data['pending_withdraw'] = $admin_wallet->pending_withdraw ?? 0;
        $data['delivery_charge_earned'] = $admin_wallet->delivery_charge_earned ?? 0;
        $data['collected_cash'] = $admin_wallet->collected_cash ?? 0;
        $data['total_tax_collected'] = $admin_wallet->total_tax_collected ?? 0;
        $mitra = Mitra::with('wallet')->find(auth('mitra')->id());

        return view('mitra-views.system.dashboard', compact('data', 'seller_data', 'commission_data', 'mitra'));
    }

    public function is_online(Request $request)
    {
        $id = auth('mitra')->id();
        $mitra = Mitra::with('wallet')->find($id);
        if ($request->online == 1) {
            $saldo = $mitra->wallet->total_earning;
            $minim = Helpers::minimal_online();
            if ($saldo < $minim) {
                return response()->json(['status' => '404', 'message' => translate('Your_balance_is_not_sufficient_to_receive_the_order/booking')]);
            }
        }
        $mitra->is_online = $request->online;
        $mitra->save();

        return response()->json(['status' => '200', 'message' => translate('Successfully_change_online_status')]);
    }

    public function topUp(Request $request)
    {
        $val = $request['nominal'];
        $customer = auth('mitra')->user();
        $value = $val;

        $user = [
                'given_names' => $customer->name,
                'email' => $customer->email,
                'mobile_number' => $customer->phone,
            ];

        // session()->put('transaction_ref', $tran);

        Config::$serverKey = config('midtrans.server_key');

        Config::$clientKey = config('midtrans.client_key');

        // non-relevant function only used for demo/example purpose
        $this->printExampleWarningMessage();

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

    public function topUpSuccess($id, $saldo)
    {
        $wallet = mitra_wallet::where('mitra_id', $id)->first();
        if (!$wallet) {
            $wallet = new mitra_wallet();
            $wallet->mitra_id = $id;
        }

        $wallet->total_earning += $saldo;

        $walletHistory = new mitra_wallet_histories();
        $walletHistory->mitra_id = $id;
        $walletHistory->amount = $saldo;
        $walletHistory->payment = 'topup';
        $walletHistory->save();

        $wallet->save();
        Toastr::success('successfully added your balance');

        return redirect()->route('mitra.mitra.home');
    }

    public function printExampleWarningMessage()
    {
        if (strpos(Config::$serverKey, 'your ') != false) {
            echo '<code>';
            echo '<h4>Please set your server key from sandbox</h4>';
            echo 'In file: '.__FILE__;
            echo '<br>';
            echo '<br>';
            echo htmlspecialchars('Config::$serverKey = \'<your server key>\';');
            die();
        }
    }

    public function order_stats_data()
    {
        $today = session()->has('statistics_type') && session('statistics_type') == 'today' ? 1 : 0;
        $this_month = session()->has('statistics_type') && session('statistics_type') == 'this_month' ? 1 : 0;

        $pending = Order::where(['seller_is' => 'seller'])->where(['mitra_id' => auth('mitra')->id()])->where(['order_status' => 'pending'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $confirmed = Order::where(['seller_is' => 'seller'])->where(['mitra_id' => auth('mitra')->id()])->where(['order_status' => 'confirmed'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $processing = Order::where(['seller_is' => 'seller'])->where(['mitra_id' => auth('mitra')->id()])->where(['order_status' => 'processing'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $out_for_delivery = Order::where(['seller_is' => 'seller'])->where(['mitra_id' => auth('mitra')->id()])->where(['order_status' => 'out_for_delivery'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $delivered = Order::where(['seller_is' => 'seller'])->where(['mitra_id' => auth('mitra')->id()])->where(['order_status' => 'delivered'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $canceled = Order::where(['seller_is' => 'seller'])->where(['mitra_id' => auth('mitra')->id()])->where(['order_status' => 'canceled'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $returned = Order::where(['seller_is' => 'seller'])->where(['mitra_id' => auth('mitra')->id()])->where(['order_status' => 'returned'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();
        $failed = Order::where(['seller_is' => 'seller'])->where(['mitra_id' => auth('mitra')->id()])->where(['order_status' => 'failed'])
            ->when($today, function ($query) {
                return $query->whereDate('created_at', Carbon::today());
            })
            ->when($this_month, function ($query) {
                return $query->whereMonth('created_at', Carbon::now());
            })
            ->count();

        $data = [
            'pending' => $pending,
            'confirmed' => $confirmed,
            'processing' => $processing,
            'out_for_delivery' => $out_for_delivery,
            'delivered' => $delivered,
            'canceled' => $canceled,
            'returned' => $returned,
            'failed' => $failed,
        ];

        return $data;
    }
}
