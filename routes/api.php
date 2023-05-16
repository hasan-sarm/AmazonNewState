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

Route::post('register','RegisterController@register');
Route::post('login','RegisterController@login');
Route::middleware('auth:api')->post('logout','RegisterController@logout');

Route::middleware('auth:api')->group(function (){
    Route::resource('product','ProductController');
    Route::get('products/user/search','ProductController@search');
    Route::get('products/sort','ProductController@sorting');
    Route::post('products/{product_id}/comments/store','CommentController@store');
    Route::get('products/{product_id}/comments','CommentController@list');
});

