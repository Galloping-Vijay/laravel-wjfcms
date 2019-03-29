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
use \Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'index'],function(){
   Route::get('/','IndexController@index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group([ 'prefix' => 'test'], function () {
    Route::get('index', 'TestController@index');
});

//后台认证路由
//Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
//    Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
//    Route::post('login', 'LoginController@login');
////    Route::group(['prefix' => 'login'], function () {
////        // 登录页面
////        Route::get('index', 'LoginController@index')->middleware('admin.login');
////        // 退出
////        Route::get('logout', 'LoginController@logout');
////    });
//});
Route::get('admin/login', 'Admin\LoginController@showLoginForm')->name('admin.login');
Route::post('admin/login', 'Admin\LoginController@login');
Route::get('admin/register', 'Admin\RegisterController@showRegistrationForm')->name('admin.register');
Route::post('admin/register', 'Admin\RegisterController@register');
Route::post('admin/logout', 'Admin\LoginController@logout')->name('admin.logout');
Route::get('admin', 'AdminController@index')->name('admin.home');
