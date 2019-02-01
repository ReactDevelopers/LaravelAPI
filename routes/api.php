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


Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');
Route::post('logout', 'PassportController@logout');

 
Route::middleware('auth:api')->group(function () {
	Route::get('getCategoryList','ApiController@getCategoryList');
	Route::get('getProductList','ApiController@getProductList');
	Route::get('getProductListCat/{id}','ApiController@getProductListCat');
    Route::get('user', 'PassportController@details');
 
    // Route::resource('products', 'ProductController');
});