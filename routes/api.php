<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'API\AllUsers\UserController@login');
Route::post('register', 'API\AllUsers\UserController@register');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('addproducts','API\Products\ProductController@createProduct');
    Route::PUT('updateproduct/{id}','API\Products\ProductController@updateProduct');
    Route::delete('deleteproduct/{id}','API\Products\ProductController@deleteProduct');
    Route::get('allproducts','API\Products\ProductController@index');
    Route::get('list','API\AllUsers\UserController@Users');

});

