<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


//注册,登陆,登出
Route::namespace('Auth')->group(function () {
    Route::post('/register', 'RegisterController@register');
    Route::post('/login', 'LoginController@login');
    Route::post('/logout', 'LoginController@logout');
});

//管理员后台
Route::middleware('auth:api')->namespace('Admin')->prefix('admin')->group(function () {
    //管理员
    Route::prefix('user')->group(function () {
        Route::get('/', 'UserController@index');
        //Route::get('/user', 'UserController@show');
        Route::post('/', 'UserController@store');
        Route::put('/', 'UserController@update');
        Route::delete('/', 'UserController@destroy');
    });

    //api来源
    Route::prefix('source')->group(function () {
        Route::get('/', 'SourceController@index');
        //Route::get('/source', 'SourceController@show');
        Route::post('/', 'SourceController@store');
        Route::put('/', 'SourceController@update');
        Route::delete('/', 'SourceController@destroy');
    });

    //api接口
    Route::prefix('api')->group(function () {
        Route::get('/initIndex', 'ApiController@initIndex');
        Route::get('/', 'ApiController@index');
        //Route::get('/api', 'ApiController@show');
        Route::post('/', 'ApiController@store');
        Route::get('/initAdd', 'ApiController@initAdd');
        Route::get('/initEdit', 'ApiController@initEdit');
        Route::put('/', 'ApiController@update');
        Route::delete('/', 'ApiController@destroy');
    });

    //api接口详情
    Route::prefix('api_details')->group(function () {
        Route::get('/initIndex', 'ApiDetailsController@initIndex');
        Route::get('/', 'ApiDetailsController@index');
        //Route::get('/api', 'ApiDetailsController@show');
        Route::post('/', 'ApiDetailsController@store');
        Route::get('/initAdd', 'ApiDetailsController@initAdd');
        Route::get('/initEdit', 'ApiDetailsController@initEdit');
        Route::put('/', 'ApiDetailsController@update');
        Route::delete('/', 'ApiDetailsController@destroy');
    });

    //类型接口
    Route::prefix('type')->group(function () {
        Route::get('/', 'TypeController@index');
        //Route::get('/type', 'TypeController@show');
        Route::post('/', 'TypeController@store');
        Route::put('/', 'TypeController@update');
        Route::delete('/', 'TypeController@destroy');
    });
});
