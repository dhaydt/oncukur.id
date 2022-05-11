<?php

namespace App\Http\Controllers\Mitra\Auth;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Mitra;
use App\Model\Shop;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register()
    {
        $outlet = Shop::get();

        return view('mitra-views.auth.register', compact('outlet'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'outlet_id' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:mitras',
            'password' => 'required|min:8',
        ], [
            'outlet.required' => 'Please select available outlet!',
            'name' => 'Your fullname is required',
            'email.required' => 'Your Email is required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $check = Mitra::where('email', $request->email)->first();
        $checkPhone = Mitra::where('phone', $request->phone)->first();

        if (isset($check)) {
            Toastr::warning('Email already exist');

            return redirect()->back()->withInput();
        }

        if (isset($checkPhone)) {
            Toastr::warning('Phone already exist');

            return redirect()->back()->withInput();
        }

        $mitra = new Mitra();
        $mitra->name = $request->name;
        $mitra->shop_id = $request->outlet_id;
        $mitra->phone = $request->phone;
        $mitra->email = $request->email;
        $mitra->status = 'pending';
        $mitra->password = bcrypt($request->password);
        $mitra->birthdate = $request->birthdate;
        $mitra->ktp = ImageManager::upload('ktp', 'png', $request->file('ktp'));
        $mitra->save();

        return redirect()->route('mitra.login');
    }
}
