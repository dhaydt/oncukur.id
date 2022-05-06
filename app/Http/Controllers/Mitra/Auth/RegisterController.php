<?php

namespace App\Http\Controllers\Mitra\Auth;

use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function register()
    {
        return view('mitra-views.auth.register');
    }
}
