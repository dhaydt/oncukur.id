<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use App\Http\Controllers\Mitra\Auth\RegisterController;
use App\Http\Controllers\Mitra\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'mitra', 'prefix' => 'mitra', 'as' => 'mitra.'], function () {
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('register', '\App\Http\Controllers\Mitra\Auth\RegisterController@register')->name('register');
        Route::get('login', '\App\Http\Controllers\Mitra\Auth\LoginController@login')->name('login');
        Route::get('logout', '\App\Http\Controllers\Mitra\Auth\LoginController@logout')->name('logout');
        Route::post('store', '\App\Http\Controllers\Mitra\Auth\RegisterController@store')->name('register.store');
        Route::post('post-login', '\App\Http\Controllers\Mitra\Auth\LoginController@store')->name('login.store');

        Route::get('check/{id}', [RegisterController::class, 'check'])->name('check');
        Route::post('verify', [RegisterController::class, 'verify'])->name('verify');

        Route::get('forgot-password', '\App\Http\Controllers\Mitra\Auth\ForgotPasswordController@forgot_password')->name('forgot-password');
        Route::post('forgot-password', '\App\Http\Controllers\Mitra\Auth\ForgotPasswordController@reset_password_request');
        Route::get('reset-password', '\App\Http\Controllers\Mitra\Auth\ForgotPasswordController@reset_password_index')->name('reset-password');
        Route::post('reset-password', '\App\Http\Controllers\Mitra\Auth\ForgotPasswordController@reset_password_submit');
    });

    // authenticated mitra
    Route::group(['middleware' => ['mitra']], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('mitra.home');

        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('view', '\App\Http\Controllers\Mitra\ProfileController@view')->name('view');
            Route::get('update/{id}', '\App\Http\Controllers\Mitra\ProfileController@edit')->name('update');
            Route::post('update/{id}', '\App\Http\Controllers\Mitra\ProfileController@update');
            Route::post('settings-password', '\App\Http\Controllers\Mitra\ProfileController@settings_password_update')->name('settings-password');
        });
    });
});
