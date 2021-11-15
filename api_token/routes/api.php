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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//api-register
Route::post('register','Api\RegisterController@register');
//login-create_token
Route::post('login','Api\LoginController@login');
//danh sach san pham
Route::get('product','Api\ProductController@index');
//refresh_token
Route::post('refresh-token','Api\LoginController@refreshtoken');
//delete_token
Route::delete('delete-token','Api\LoginController@deletetoken');
