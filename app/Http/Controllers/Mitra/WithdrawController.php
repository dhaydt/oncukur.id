<?php

namespace App\Http\Controllers\Mitra;

use App\CPU\BackEndHelper;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\mitra_wallet;
use App\Model\WithdrawRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    public function w_request(Request $request)
    {
        $wallet = mitra_wallet::where('mitra_id', auth('mitra')->id())->first();
        if (($wallet->total_earning) >= Convert::usd($request['amount']) && $request['amount'] > 1) {
            DB::table('withdraw_requests')->insert([
                'mitra_id' => auth('mitra')->id(),
                'amount' => Convert::usd($request['amount']),
                'transaction_note' => null,
                'approved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $wallet->total_earning -= BackEndHelper::currency_to_usd($request['amount']);
            $wallet->pending_withdraw += BackEndHelper::currency_to_usd($request['amount']);
            $wallet->save();

            Toastr::success('Withdraw request has been sent.');

            return redirect()->back();
        }

        Toastr::error('invalid request.!');

        return redirect()->back();
    }

    public function close_request($id)
    {
        $withdraw_request = WithdrawRequest::find($id);
        $wallet = mitra_wallet::where('mitra_id', auth()->guard('mitra')->user()->id)->first();
        if (isset($withdraw_request) && $withdraw_request->approved == 0) {
            $wallet->total_earning += $withdraw_request['amount'];
            $wallet->pending_withdraw -= $withdraw_request['amount'];
            $wallet->save();
            $withdraw_request->delete();
            Toastr::success('Request closed!');
        } else {
            Toastr::error('Invalid request');
        }

        return back();
    }

    public function status_filter(Request $request)
    {
        session()->put('withdraw_status_filter', $request['withdraw_status_filter']);

        return response()->json(session('withdraw_status_filter'));
    }

    public function list()
    {
        $all = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'all' ? 1 : 0;
        $active = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'approved' ? 1 : 0;
        $denied = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'denied' ? 1 : 0;
        $pending = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'pending' ? 1 : 0;

        $withdraw_requests = WithdrawRequest::with(['mitra'])
            ->where(['seller_id' => auth('seller')->id()])
            ->when($all, function ($query) {
                return $query;
            })
            ->when($active, function ($query) {
                return $query->where('approved', 1);
            })
            ->when($denied, function ($query) {
                return $query->where('approved', 2);
            })
            ->when($pending, function ($query) {
                return $query->where('approved', 0);
            })
            ->orderBy('id', 'desc')
            ->paginate(Helpers::pagination_limit());

        return view('mitra-views.withdraw.list', compact('withdraw_requests'));
    }
}
