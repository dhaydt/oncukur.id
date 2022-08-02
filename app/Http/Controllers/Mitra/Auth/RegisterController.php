<?php

namespace App\Http\Controllers\Mitra\Auth;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Mitra;
use App\Model\PhoneOrEmailVerification;
use App\Model\Shop;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register()
    {
        $outlet = Shop::where('status', 1)->get();

        return view('mitra-views.auth.register', compact('outlet'));
    }

    public function store(Request $request)
    {
        $user = Mitra::where('email', $request->email)->orWhere('phone', $request->phone)->first();
        if (isset($user) && $user->is_email_verified == 0) {
            return redirect(route('mitra.auth.check', [$user->id]));
        }
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
        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
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

        $email_verification = Helpers::get_business_settings('email_verification');

        if ($email_verification && !$mitra->is_email_verified) {
            return redirect(route('mitra.auth.check', [$mitra->id]));
        }

        return redirect()->route('mitra.auth.login');
    }

    public static function check($id)
    {
        $user = Mitra::find($id);

        $token = rand(1000, 9999);
        DB::table('phone_or_email_verifications')->insert([
            'phone_or_email' => $user->email,
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $email_verification = Helpers::get_business_settings('email_verification');
        if ($email_verification && !$user->is_email_verified) {
            try {
                Mail::to($user->email)->send(new \App\Mail\EmailVerification($token));
                $response = translate('check_your_email');
                Toastr::success($response);
            } catch (\Exception $exception) {
                $response = translate('email_failed');
                Toastr::error($response);
            }
        }

        return view('mitra-views.auth.verify', compact('user'));
    }

    public static function verify(Request $request)
    {
        Validator::make($request->all(), [
            'token' => 'required',
        ]);

        $email_status = BusinessSetting::where('type', 'email_verification')->first()->value;
        $phone_status = BusinessSetting::where('type', 'phone_verification')->first()->value;

        $user = Mitra::find($request->id);
        $verify = PhoneOrEmailVerification::where(['phone_or_email' => $user->email, 'token' => $request['token']])->first();

        if ($email_status == 1 || ($email_status == 0 && $phone_status == 0)) {
            if (isset($verify)) {
                try {
                    $user->is_email_verified = 1;
                    $user->save();
                    $verify->delete();
                } catch (\Exception $exception) {
                    Toastr::info('Try again');
                }

                Toastr::success(translate('verification_done_successfully'));
            } else {
                Toastr::error(translate('Verification_code_or_OTP mismatched'));

                return redirect()->back();
            }
        } else {
            if (isset($verify)) {
                try {
                    $user->is_phone_verified = 1;
                    $user->save();
                    $verify->delete();
                } catch (\Exception $exception) {
                    Toastr::info('Try again');
                }

                Toastr::success('Verification Successfully Done');
            } else {
                Toastr::error('Verification code/ OTP mismatched');
            }
        }

        return redirect(route('mitra.auth.login'));
    }
}
