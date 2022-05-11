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
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('store', 'RegisterController@store')->name('register.store');
        Route::post('post-login', 'LoginController@store')->name('login.store');
    });

    Route::group(['midleware' => ['mitra']], function () {
        Route::get('/mitra', function () {
            return 'welcome to mitra home';
        })->name('mitra.home');
    });
});
