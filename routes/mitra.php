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

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Mitra', 'prefix' => 'mitra', 'as' => 'mitra.'], function () {
    Route::get('/', function () {
        return 'work';
    });

    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('register', 'RegisterController@register')->name('register');
        Route::get('login', 'RegisterController@login')->name('login');
    });
});
