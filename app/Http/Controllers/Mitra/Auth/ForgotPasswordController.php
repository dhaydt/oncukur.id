<?php

namespace App\Http\Controllers\Mitra\Auth;

use App\Http\Controllers\Controller;
use App\Model\Mitra;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:seller', ['except' => ['logout']]);
    }

    public function forgot_password()
    {
        return view('mitra-views.auth.forgot-password');
    }

    public function reset_password_request(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $seller = Mitra::Where(['email' => $request['email']])->first();

        if (isset($seller)) {
            $token = Str::random(120);
            DB::table('password_resets')->insert([
                'email' => $seller['email'],
                'token' => $token,
                'created_at' => now(),
            ]);
            $reset_url = url('/').'/mitra/auth/reset-password?token='.$token;
            Mail::to($seller['email'])->send(new \App\Mail\PasswordResetMail($reset_url));

            Toastr::success('Check your email. Password reset url sent.');

            return back();
        }

        Toastr::error('No such email found!');

        return back();
    }

    public function reset_password_index(Request $request)
    {
        $data = DB::table('password_resets')->where(['token' => $request['token']])->first();
        if (isset($data)) {
            $token = $request['token'];

            return view('mitra-views.auth.reset-password', compact('token'));
        }
        Toastr::error('Invalid URL.');

        return redirect('/mitra/auth/login');
    }

    public function reset_password_submit(Request $request)
    {
        $request->validate([
            'password' => 'required|same:confirm_password|min:8',
        ]);

        $data = DB::table('password_resets')->where(['token' => $request['reset_token']])->first();
        if (isset($data)) {
            $email = !empty($data->email) ? $data->email : $data->identity;
            Mitra::where('email', $email)->update([
                'password' => bcrypt($request['confirm_password']),
            ]);
            Toastr::success('Password reset successfully.');
            DB::table('password_resets')->where(['token' => $request['reset_token']])->delete();

            return redirect('/mitra/auth/login');
        }
        Toastr::error('Invalid URL.');

        return redirect('/mitra/auth/login');
    }
}
