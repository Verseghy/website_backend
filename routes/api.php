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
    Route::get('listFeaturedPosts', 'PostsController@listFeaturedPosts');
    Route::get('getPreview', 'PostsController@getPreview');
    Route::get('getPostsByYearMonth', 'PostsController@byYearMonth');
    Route::get('getCountByMonth', 'PostsController@countByMonth');
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

Route::prefix('colleagues')->group(function () {
    Route::get('listColleagues', 'ColleaguesController@listColleagues');
});

Route::prefix('pages')->group(function () {
    Route::get('getPageBySlug', '\App\Http\Controllers\PageController@getPageBySlug');
});

Route::prefix('menu')->group(function () {
    Route::get('getMenuItems', '\App\Http\Controllers\MenuController@getMenuItems');
});
