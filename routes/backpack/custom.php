<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('labels', 'LabelsCrudController');
    Route::crud('authors', 'AuthorsCrudController');
    Route::crud('posts', 'PostsCrudController');
    Route::crud('colleagues', 'ColleaguesCrudController');
    Route::crud('menus', 'MenusCrudController');
    Route::crud('canteens', 'CanteensCrudController');
    Route::crud('newsletter', 'NewsletterCrudController');
    Route::crud('events', 'EventsCrudController');
    Route::get('dashboard', function () {
        return view('dashboard');
    });
}); // this should be the absolute last line of this file
