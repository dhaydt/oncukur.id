<?php

namespace App\Http\Controllers\api\v3\mitra\auth;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Mitra;
use App\Model\SellerWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $mitra = Mitra::where(['email' => $request['email']])->first();

        if (isset($mitra) && $mitra['status'] == 'approved' && auth('mitra')->attempt($data)) {
            $token = rand(1000, 9999);
            Mitra::where('id', $mitra->id)->update([
                'otp_login' => $token,
            ]);

            try {
                Mail::to($request['email'])->send(new \App\Mail\EmailVerification($token));
                $response = 'check_your_email';
            } catch (\Exception $exception) {
                $response = 'email_failed';
            }

            return response()->json([
                'message' => $response,
                'otp' => 'active',
            ], 200);

            $token = Str::random(50);

            Mitra::where(['id' => auth('mitra')->id()])->update(['auth_token' => $token]);
            if (SellerWallet::where('seller_id', $mitra['id'])->first() == false) {
                DB::table('seller_wallets')->insert([
                    'seller_id' => $mitra['id'],
                    'withdraw' => 0,
                    'comission_given' => 0,
                    'total_earning' => 0,
                    'pending_withdraw' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json(['status' => 'success', 'token' => $token]);
        } else {
            $errors = [];

            array_push($errors, [
                'code' => 'auth-001',
                'message' => 'Invalid credential or account not verified yet',
            ]);

            return response()->json([
                'errors' => $errors,
            ], 401);
        }
    }

    public function otp_login_verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'otp' => 'required',
            'password' => 'required',
            'device_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $mitra = Mitra::where(['email' => $request->email, 'otp_login' => $request->otp])->first();
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        // dd($data);
        if (isset($mitra) && auth('mitra')->attempt($data)) {
            $token = Str::random(50);

            Mitra::where(['id' => auth('mitra')->id()])->update(['auth_token' => $token, 'device_id' => $request->device_id]);
            if (SellerWallet::where('seller_id', $mitra['id'])->first() == false) {
                DB::table('seller_wallets')->insert([
                    'seller_id' => $mitra['id'],
                    'withdraw' => 0,
                    'comission_given' => 0,
                    'total_earning' => 0,
                    'pending_withdraw' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json(['status' => 'success', 'token' => $token]);
        } else {
            return response()->json('invalid token or password');
        }
    }
}
