<?php

namespace App\Http\Controllers\api\v3\mitra;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MitraController extends Controller
{
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
}
