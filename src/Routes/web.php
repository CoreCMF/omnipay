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
/*
|--------------------------------------------------------------------------
| Admin后台路由设置 routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'Omnipay', 'middleware' => 'webOmnipay','namespace' => 'CoreCMF\Omnipay\App\Http\Controllers', 'as' => 'Omnipay.'], function () {
    Route::get('{gatewayNmae}',                 [ 'as' => 'pay', 'uses' => 'OmnipayController@pay']);
    Route::get('{gatewayNmae}/callback',        [ 'as' => 'callback', 'uses' => 'OmnipayController@callback']);
    Route::post('{gatewayNmae}/callback',       [ 'as' => 'callback', 'uses' => 'OmnipayController@callback']);
    Route::get('{gatewayNmae}/notify',          [ 'as' => 'notify', 'uses' => 'OmnipayController@notify']);
    Route::post('{gatewayNmae}/notify',         [ 'as' => 'notify', 'uses' => 'OmnipayController@notify']);
});
