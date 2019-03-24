<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Article;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('test', function (Request $request) {
    return substr(md5(uniqid()), 0, 30) . substr(md5(uniqid()), 0, 30);
});


Route::post('register', 'Auth\RegisterController@register');

Route::get('articles', 'APi\ArticleController@index');
Route::get('articles/{article}', 'Api\ArticleController@show');
Route::post('articles', 'Api\ArticleController@store');
Route::put('articles/{article}', 'Api\ArticleController@update');
Route::delete('articles/{article}', 'Api\ArticleController@delete');
