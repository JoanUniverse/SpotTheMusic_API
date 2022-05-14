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

Route::post('login', 'App\Http\Controllers\LoginController@login');

Route::group(
    ['prefix' => 'categories'],
    function () {
        Route::get('', 'App\Http\Controllers\CategoryController@index');
        Route::get('{id}', 'App\Http\Controllers\CategoryController@show');
        Route::delete('{id}', 'App\Http\Controllers\CategoryController@delete');
        Route::post('', 'App\Http\Controllers\CategoryController@store');
        Route::put('{id}', 'App\Http\Controllers\CategoryController@update');
    }
);

Route::group(
    ['prefix' => 'events'],
    function () {
        Route::get('', 'App\Http\Controllers\EventController@index');
        Route::get('{id}', 'App\Http\Controllers\EventController@show');
        Route::delete('{id}', 'App\Http\Controllers\EventController@delete');
        Route::post('', 'App\Http\Controllers\EventController@store');
        Route::put('{id}', 'App\Http\Controllers\EventController@update');
    }
);

Route::group(
    ['prefix' => 'users'],
    function () {
        Route::get('', 'App\Http\Controllers\UserController@index');
        Route::get('{id}', 'App\Http\Controllers\UserController@show');
        Route::delete('{id}', 'App\Http\Controllers\UserController@delete');
        Route::post('', 'App\Http\Controllers\UserController@store');
        Route::put('{id}', 'App\Http\Controllers\UserController@update');
        Route::post('{id}/image', 'App\Http\Controllers\UserController@picture');
    }
);

Route::group(
    ['prefix' => 'followers'],
    function () {
        Route::get('', 'App\Http\Controllers\FollowerController@index');
        Route::get('{id}', 'App\Http\Controllers\FollowerController@show');
        Route::get('{id}/follows', 'App\Http\Controllers\FollowerController@showFollows');
        Route::get('{id}/new', 'App\Http\Controllers\FollowerController@showNewFollowers');
        Route::delete('{id}', 'App\Http\Controllers\FollowerController@delete');
        Route::post('', 'App\Http\Controllers\FollowerController@store');
        Route::put('{id}', 'App\Http\Controllers\FollowerController@update');
    }
);

Route::group(
    ['prefix' => 'posts'],
    function () {
        Route::get('', 'App\Http\Controllers\PostController@index');
        Route::get('{id}', 'App\Http\Controllers\PostController@show');
        Route::get('{id}/follows', 'App\Http\Controllers\PostController@showFollowed');
        Route::delete('{id}', 'App\Http\Controllers\PostController@delete');
        Route::post('', 'App\Http\Controllers\PostController@store');
        Route::put('{id}', 'App\Http\Controllers\PostController@update');
    }
);

Route::group(
    ['prefix' => 'messages'],
    function () {
        Route::get('{idFrom}/{idTo}', 'App\Http\Controllers\MessageController@index');
        Route::delete('{id}', 'App\Http\Controllers\MessageController@delete');
        Route::post('', 'App\Http\Controllers\MessageController@store');
    }
);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
