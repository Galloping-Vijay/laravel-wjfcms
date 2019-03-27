<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

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
// 为了方便测试，我们先忽略 CSRF 校验
\Laravel\Passport\Passport::$ignoreCsrfToken = true;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('test', function (Request $request) {
    return substr(md5(uniqid()), 0, 30) . substr(md5(uniqid()), 0, 30);
});


Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('articles', 'APi\ArticleController@index');
    Route::get('articles/{article}', 'APi\ArticleController@show');
    Route::post('articles', 'APi\ArticleController@store');
    Route::put('articles/{article}', 'APi\ArticleController@update');
    Route::delete('articles/{article}', 'APi\ArticleController@delete');
});
