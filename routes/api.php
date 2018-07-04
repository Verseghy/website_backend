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

Route::prefix('posts')->group(function () {
    Route::get('getPost', 'PostsController@byId');
    Route::get('getPostsByAuthor', 'PostsController@byAuthor');
    Route::get('getPostsByLabel', 'PostsController@byLabel');
    Route::get('listPosts', 'PostsController@listPosts');
});

Route::prefix('events')->group(function () {
    Route::get('getEventsByMonth', 'EventsController@byMonth');
});
