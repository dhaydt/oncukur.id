<?php

namespace App\Http\Middleware;

use App\CPU\Helpers;
use Closure;

class MitraDeviceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = Helpers::get_mitra_by_token($request);
        $device_user = $request->device_id;
        $device_saved = $data['data']->device_id;
        if ($device_user !== $device_saved) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Your device is not registered, please login again',
            ]);
        }

        return $next($request);
    }
}
