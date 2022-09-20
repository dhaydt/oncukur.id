<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'api\v3', 'prefix' => 'v3', 'middleware' => ['api_lang']], function () {
    Route::group(['prefix' => 'mitra', 'namespace' => 'mitra'], function () {
        Route::get('monthly-earning', 'MitraController@monthly_earning');
        Route::get('monthly-commission-given', 'MitraController@monthly_commission_given');
        Route::get('monthly-commission-given-outlet', 'MitraController@monthly_commission_given_outlet');
        Route::get('transactions', 'MitraController@transaction');

        Route::post('balance-withdraw', 'MitraController@withdraw_request');
        Route::post('close-withdraw-request', 'MitraController@close_withdraw_request');

        Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
            Route::post('login', 'LoginController@login');
            Route::post('verify-login-otp', 'LoginController@otp_login_verify');

            Route::post('forgot-password', 'ForgotPassword@reset_password_request');
            Route::put('reset-password', 'ForgotPassword@reset_password_submit');
        });

        Route::group(['middleware' => 'device-mitra'], function () {
            Route::post('check-device', 'MitraController@checkDevice');
        });

        Route::get('mitra-info', 'MitraController@mitra_info');
        Route::post('mitra-update', 'MitraController@update');

        Route::group(['prefix' => 'orders'], function () {
            Route::get('list', 'OrderController@list');
            Route::get('/{id}', 'OrderController@details');
            Route::post('order-detail-status/{id}', 'OrderController@order_detail_status');
        });
    });
});
