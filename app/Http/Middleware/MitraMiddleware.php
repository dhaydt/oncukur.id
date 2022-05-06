<?php

namespace App\Http\Middleware;

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

        return redirect()->route('mitra.auth.login');
    }
}
