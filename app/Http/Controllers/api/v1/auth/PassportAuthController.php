<?php

namespace App\Http\Controllers\api\v1\auth;

use App\CPU\Helpers;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PassportAuthController extends Controller
{
    public function register(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
        ], [
            'name.required' => 'The first name field is required.',
            'email' => 'Your address address is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => Helpers::error_processor($validator)], 403);
        }
        $temporary_token = Str::random(40);
        $user = User::create([
            'f_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => 1,
            'password' => bcrypt($request->password),
            'temporary_token' => $temporary_token,
        ]);

        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');
        if ($phone_verification && !$user->is_phone_verified) {
            return response()->json(['status' => 'success', 'temporary_token' => $temporary_token], 200);
        }
        if ($email_verification && !$user->is_email_verified) {
            return response()->json(['status' => 'success', 'temporary_token' => $temporary_token], 200);
        }

        $token = $user->createToken('LaravelAuthApp')->accessToken;

        return response()->json([
            'status' => 'success',
            'token' => $token, ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => Helpers::error_processor($validator)], 403);
        }

        $user_id = $request['email'];
        if (filter_var($user_id, FILTER_VALIDATE_EMAIL)) {
            $medium = 'email';
        } else {
            $count = strlen(preg_replace("/[^\d]/", '', $user_id));
            if ($count >= 9 && $count <= 15) {
                $medium = 'phone';
            } else {
                $errors = [];
                array_push($errors, ['code' => 'email', 'message' => 'Invalid email address or phone number']);

                return response()->json([
                    'status' => 'fail',
                    'errors' => $errors,
                ], 403);
            }
        }

        $data = [
            $medium => $user_id,
            'password' => $request->password,
        ];

        $user = User::where([$medium => $user_id])->first();

        if (isset($user) && $user->is_active && auth()->attempt($data)) {
            $user->temporary_token = Str::random(40);
            $user->save();

            $phone_verification = Helpers::get_business_settings('phone_verification');
            $email_verification = Helpers::get_business_settings('email_verification');
            if ($phone_verification && !$user->is_phone_verified) {
                return response()->json(['status' => 'success', 'temporary_token' => $user->temporary_token], 200);
            }
            if ($email_verification && !$user->is_email_verified) {
                return response()->json(['status' => 'success', 'temporary_token' => $user->temporary_token], 200);
            }

            $token = rand(1000, 9999);
            User::where('id', $user->id)->update([
                'otp_login' => $token,
            ]);

            try {
                Mail::to($request['email'])->send(new \App\Mail\EmailVerification($token));
                $response = translate('check_your_email');
            } catch (\Exception $exception) {
                $response = translate('email_failed');
            }

            return response()->json([
                'status' => 'success',
                'message' => $response,
                'otp' => 'active',
            ], 200);

        // $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
        //     return response()->json(['status' => 'success', 'token' => $token], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => translate('Customer_not_found_or_Account_has_been_suspended')]);

            return response()->json([
                'status' => 'fail',
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
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => Helpers::error_processor($validator)], 403);
        }

        $user = User::where(['email' => $request->email, 'otp_login' => $request->otp])->first();
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        // dd($data);
        if (isset($user) && auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;

            return response()->json(['status' => 'success', 'token' => $token], 200);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'invalid token']);
        }
    }
}
