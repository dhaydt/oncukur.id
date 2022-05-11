<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'api\v3', 'prefix' => 'v3', 'middleware' => ['api_lang']], function () {
    Route::group(['prefix' => 'mitra', 'namespace' => 'mitra'], function () {
        Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
            Route::post('login', 'LoginController@login');
        });

        Route::get('mitra-info', 'MitraController@mitra_info');
    });
});
