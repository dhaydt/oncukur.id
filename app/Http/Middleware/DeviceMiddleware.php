<?php

namespace App\Http\Middleware;

use Closure;

class DeviceMiddleware
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
        $device_user = $request->device_id;
        $device_saved = $request->user()->device_id;
        if ($device_user !== $device_saved) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Your device is not registered, please login again',
            ]);
        }

        return $next($request);
    }
}
