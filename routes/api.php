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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix'        => 'user',
//    'middleware'    => [
//        '',
//    ],
], function () {
    Route::get('read/{id}', 'UserController@read');
    Route::get('read_all', 'UserController@readAll');
    Route::put('update/{id}', 'UserController@update');
    Route::post('create', 'UserController@create');
    Route::delete('delete/{id}', 'UserController@delete');

});


