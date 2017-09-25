<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'api', 'middleware' => 'api', 'namespace' => 'CoreCMF\Omnipay\App\Http\Controllers\Api', 'as' => 'api.'], function () {
    /*
    |--------------------------------------------------------------------------
    | 需要用户认证路由模块
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => []], function () {
    	// 后台仪表盘路由
	    Route::group(['prefix' => 'omnipay', 'as' => 'omnipay.'], function () {
        //配置
		    Route::post('config',                ['as' => 'config',     'uses' => 'ConfigController@index']);
        Route::post('config/update',         ['as' => 'config.update','uses' => 'ConfigController@update']);
        Route::post('config/file',           ['as' => 'config.file','uses' => 'ConfigController@file']);
        //订单
        Route::post('order',                 ['as' => 'order',     'uses' => 'OrderController@index']);
		  });

	 });
});
