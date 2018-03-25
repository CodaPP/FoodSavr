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

Route::get('/', function () {
    return view('welcome');
});

Auth::Routes();

Route::get('/user/getFridgeItems', 'FridgeController@getUserItems');

Route::post('/user/addFridgeItems', 'FridgeController@addUserItems');

Route::post('/user/donateItems', 'FridgeController@donateItems');

Route::post('/user/sendToken', 'UserController@sendToken');

Route::get('/user/getToken', 'UserController@getToken');

Route::post('/products/updateInfo', 'ProductController@updateInfo');
