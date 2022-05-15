<?php

namespace App\Http\Middleware;

use Brian2694\Toastr\Facades\Toastr;
use Closure;

class MitraMiddleware
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
        if (auth('mitra')->check() && auth('mitra')->user()->status == 'approved') {
            return $next($request);
        }

        auth()->guard('mitra')->logout();

        Toastr::warning('Please input your mitra account credentials');

        return redirect()->route('mitra.auth.login');
    }
}
