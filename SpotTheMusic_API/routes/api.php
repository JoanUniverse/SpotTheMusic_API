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
        Route::get('/common/{idUser}', 'App\Http\Controllers\CategoryController@showCommon');
        Route::get('{id}', 'App\Http\Controllers\CategoryController@show');
        Route::delete('{id}', 'App\Http\Controllers\CategoryController@delete');
        Route::post('', 'App\Http\Controllers\CategoryController@store');
        Route::put('{id}', 'App\Http\Controllers\CategoryController@update');
        Route::get('/user/{idUser}/{idCategory}', 'App\Http\Controllers\CategoryController@AddRemove');
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
        Route::put('{id}/location', 'App\Http\Controllers\UserController@location');
        Route::post('{id}/image', 'App\Http\Controllers\UserController@picture');
        Route::get('{id}/nearby/{value}', 'App\Http\Controllers\UserController@nearUsers');
    }
);

Route::group(
    ['prefix' => 'followers'],
    function () {
        Route::get('', 'App\Http\Controllers\FollowerController@index');
        Route::get('{id}', 'App\Http\Controllers\FollowerController@show');
        Route::get('{id}/follows', 'App\Http\Controllers\FollowerController@showFollows');
        Route::get('{id}/new', 'App\Http\Controllers\FollowerController@showNewFollowers');
        Route::delete('{idFrom}/{idTo}', 'App\Http\Controllers\FollowerController@delete');
        Route::post('', 'App\Http\Controllers\FollowerController@store');
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
        Route::get('/like/{idPost}/{idUser}', 'App\Http\Controllers\PostController@likeDislike');
        Route::get('/spot/{idPost}/{idUser}', 'App\Http\Controllers\PostController@spotPost');
    }
);

Route::group(
    ['prefix' => 'messages'],
    function () {
        Route::get('/chat/{idFrom}/{idTo}', 'App\Http\Controllers\MessageController@index');
        Route::get('/list/{idUser}', 'App\Http\Controllers\MessageController@indexList');
        Route::delete('{id}', 'App\Http\Controllers\MessageController@delete');
        Route::post('', 'App\Http\Controllers\MessageController@store');
    }
);

Route::group(
    ['prefix' => 'songs'],
    function () {
        Route::get('', 'App\Http\Controllers\SongController@index');
        Route::get('/name/{name}', 'App\Http\Controllers\SongController@indexName');
        Route::get('{id}', 'App\Http\Controllers\SongController@show');
        Route::post('', 'App\Http\Controllers\SongController@store');
    }
);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
