<?php

namespace App\Http\Controllers\api\v3\mitra;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Mitra;
use Illuminate\Http\Request;

class MitraController extends Controller
{
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
}
