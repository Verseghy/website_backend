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
    CRUD::resource('labels', 'LabelsCrudController');
    CRUD::resource('authors', 'AuthorsCrudController');
    CRUD::resource('posts', 'PostsCrudController');
    CRUD::resource('colleagues', 'ColleaguesCrudController');
    CRUD::resource('menus', 'MenusCrudController');
    CRUD::resource('canteens', 'CanteensCrudController');
    CRUD::resource('newsletter', 'NewsletterCrudController');
    CRUD::resource('events', 'EventsCrudController');
    Route::get('dashboard', function () {
        return view('dashboard');
    });
}); // this should be the absolute last line of this file
