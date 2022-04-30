<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\Shop;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function create()
    {
        $country = DB::table('country')->get();

        return view('seller-views.auth.register', compact('country'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:sellers',
            'password' => 'required|min:8',
            'shop_address' => 'required',
            'shop_name' => 'required',
        ]);

        DB::transaction(function ($r) use ($request) {
            $seller = new Seller();
            $seller->f_name = $request->name;
            $seller->phone = $request->phone;
            $seller->email = $request->email;
            $seller->password = bcrypt($request->password);
            $seller->status = 'pending';
            $seller->save();

            $shop = new Shop();
            $shop->seller_id = $seller->id;
            $shop->name = $request->shop_name;
            $shop->address = $request->shop_address;
            $shop->contact = $request->phone_shop;
            $shop->child = $request->child;
            $shop->teen = $request->teen;
            $shop->adult = $request->adult;
            $shop->save();

            DB::table('seller_wallets')->insert([
                'seller_id' => $seller['id'],
                'withdrawn' => 0,
                'commission_given' => 0,
                'total_earning' => 0,
                'pending_withdraw' => 0,
                'delivery_charge_earned' => 0,
                'collected_cash' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        Toastr::success('Barber_Shop_applied_successfully!');

        return redirect()->route('seller.auth.login');
    }
}
