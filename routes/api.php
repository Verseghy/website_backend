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
    Route::get('search', 'PostsController@search');
});

Route::prefix('newsletter')->group(function () {
    Route::get('subscribe', 'NewsletterController@subscribe');
    Route::get('unsubscribe', 'NewsletterController@unsubscribe');
});

Route::prefix('events')->group(function () {
    Route::get('getEventsByMonth', 'EventsController@byMonth');
});

Route::prefix('canteen')->group(function () {
    Route::get('getCanteenMenus', 'CanteensController@getMenus');
    Route::get('getCanteenByWeek', 'CanteensController@byWeek');
});
