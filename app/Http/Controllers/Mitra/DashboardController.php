<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class DashboardController extends Controller
{
    public function index()
    {
        Toastr::info('Welcome to OnCukur Dashboard Mitra!');

        return view('mitra-views.system.dashboard');
    }
}
