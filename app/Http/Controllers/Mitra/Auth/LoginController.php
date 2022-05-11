<?php

namespace App\Http\Controllers\Mitra\Auth;

use App\Http\Controllers\Controller;
use App\Model\Mitra;
use App\Model\SellerWallet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login()
    {
        return view('mitra-views.auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $se = Mitra::where(['email' => $request['email']])->first(['status']);

        if (isset($se) && $se['status'] == 'approved' && auth('mitra')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            Toastr::info('Welcome to Mitra Dashboard!');
            if (SellerWallet::where('seller_id', auth('mitra')->id())->first() == false) {
                DB::table('seller_wallets')->insert([
                    'seller_id' => auth('mitra')->id(),
                    'withdrawn' => 0,
                    'commission_given' => 0,
                    'total_earning' => 0,
                    'pending_withdraw' => 0,
                    'delivery_charge_earned' => 0,
                    'collected_cash' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect()->route('mitra.mitra.home');
        } elseif (isset($se) && $se['status'] == 'pending') {
            return redirect()->back()->withInput($request->only('email', 'remember'))
                ->withErrors(['Your account is not approved yet.']);
        } elseif (isset($se) && $se['status'] == 'suspended') {
            return redirect()->back()->withInput($request->only('email', 'remember'))
                ->withErrors(['Your account has been suspended!.']);
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors(['Credentials does not match.']);
    }
}
