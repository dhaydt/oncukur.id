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

use App\Http\Controllers\Mitra\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'mitra', 'prefix' => 'mitra', 'as' => 'mitra.'], function () {
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('register', 'RegisterController@register')->name('register');
        Route::get('login', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');
        Route::post('store', 'RegisterController@store')->name('register.store');
        Route::post('post-login', 'LoginController@store')->name('login.store');

        Route::get('forgot-password', 'ForgotPasswordController@forgot_password')->name('forgot-password');
        Route::post('forgot-password', 'ForgotPasswordController@reset_password_request');
        Route::get('reset-password', 'ForgotPasswordController@reset_password_index')->name('reset-password');
        Route::post('reset-password', 'ForgotPasswordController@reset_password_submit');
    });

    // authenticated mitra
    Route::group(['middleware' => ['mitra']], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('mitra.home');

        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('view', 'ProfileController@view')->name('view');
            Route::get('update/{id}', 'ProfileController@edit')->name('update');
            Route::post('update/{id}', 'ProfileController@update');
            Route::post('settings-password', 'ProfileController@settings_password_update')->name('settings-password');
        });
    });
});
