<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'Web'], function () {

    //登录页面
    Route::get('login', 'LoginController@index');

    //注册页面
    Route::get('register', 'RegisterController@index');

    //登录行为
    Route::post('login', 'LoginController@login');

    //注册行为
    Route::PUT('register', 'RegisterController@register');

    //首页
    Route::get('/', 'IndexController@index');

    //主界面数据
    Route::get('init', 'IndexController@main');

    //分组
    Route::group(['prefix' => 'group'], function () {

        //添加分组
        Route::PUT('add', 'GroupController@add');

    });

    //查找
    Route::group(['prefix' => 'find'], function () {

        //主页
        Route::get('/', 'FindController@index');

        //主页
        Route::get('recommend', 'FindController@recommend');

    });

    //消息
    Route::group(['prefix' => 'message'], function () {

        //主页
        Route::get('/', 'MessageController@index');

        //列表
        Route::get('list', 'MessageController@lists');

        //发送
        Route::PUT('send', 'MessageController@send');

        //消息操作
        Route::post('update', 'MessageController@update');

    });

    //聊天
    Route::group(['prefix' => 'chat'], function () {

        //列表
        Route::get('list', 'ChatController@lists');

        //发送
        Route::PUT('send', 'ChatController@send');

    });

});
