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

    Route::get('topUp-success/{id}/{saldo}', [DashboardController::class, 'topUpSuccess']);

    // authenticated mitra
    Route::group(['middleware' => ['mitra']], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('mitra.home');
        Route::post('/is_online', [DashboardController::class, 'is_online'])->name('mitra.online');

        Route::post('/topup', [DashboardController::class, 'topUp'])->name('mitra.topup');

        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('view', '\App\Http\Controllers\Mitra\ProfileController@view')->name('view');
            Route::get('update/{id}', '\App\Http\Controllers\Mitra\ProfileController@edit')->name('update');
            Route::post('update/{id}', '\App\Http\Controllers\Mitra\ProfileController@update');
            Route::post('settings-password', '\App\Http\Controllers\Mitra\ProfileController@settings_password_update')->name('settings-password');
            Route::get('bank-edit/{id}', '\App\Http\Controllers\Mitra\ProfileController@bank_edit')->name('bankInfo');
            Route::post('bank-update/{id}', '\App\Http\Controllers\Mitra\ProfileController@bank_update')->name('bank_update');
        });

        Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
            Route::get('list/{status}', '\App\Http\Controllers\Mitra\OrderController@list')->name('list');
            Route::get('details/{id}', '\App\Http\Controllers\Mitra\OrderController@details')->name('details');
            Route::get('generate-invoice/{id}', '\App\Http\Controllers\Mitra\OrderController@generate_invoice')->name('generate-invoice');
            Route::post('status', '\App\Http\Controllers\Mitra\OrderController@status')->name('status');
            Route::post('productStatus', '\App\Http\Controllers\Mitra\OrderController@productStatus')->name('productStatus');
            Route::post('payment-status', '\App\Http\Controllers\Mitra\OrderController@payment_status')->name('payment-status');
        });

        // Messaging
        Route::group(['prefix' => 'messages', 'as' => 'messages.'], function () {
            Route::get('/chat', 'ChattingController@chat')->name('chat');
            Route::get('/message-by-user', 'ChattingController@message_by_user')->name('message_by_user');
            Route::post('/seller-message-store', 'ChattingController@seller_message_store')->name('seller_message_store');
        });

        Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.'], function () {
            Route::get('list', 'WithdrawController@list')->name('list');
            Route::get('cancel/{id}', 'WithdrawController@close_request')->name('cancel');
            Route::post('status-filter', 'WithdrawController@status_filter')->name('status-filter');
            Route::post('request', 'WithdrawController@w_request')->name('request');
            Route::delete('close/{id}', 'WithdrawController@close_request')->name('close');
        });
    });
});
