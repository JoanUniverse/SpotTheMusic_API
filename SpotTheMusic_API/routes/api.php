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

Route::group(
    ['prefix' => 'category'],
    function () {
        Route::get('', 'App\Http\Controllers\CategoryController@index');
        Route::get('{id}', 'App\Http\Controllers\CategoryController@show');
        Route::delete('{id}', 'App\Http\Controllers\CategoryController@delete');
        Route::post('', 'App\Http\Controllers\CategoryController@store');
        Route::put('{id}', 'App\Http\Controllers\CategoryController@update');
    }
);

Route::group(
    ['prefix' => 'event'],
    function () {
        Route::get('', 'App\Http\Controllers\EventController@index');
        Route::get('{id}', 'App\Http\Controllers\EventController@show');
        Route::delete('{id}', 'App\Http\Controllers\EventController@delete');
        Route::post('', 'App\Http\Controllers\EventController@store');
        Route::put('{id}', 'App\Http\Controllers\EventController@update');
    }
);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
