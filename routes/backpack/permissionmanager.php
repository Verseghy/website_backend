<?php

/*
|--------------------------------------------------------------------------
| Backpack\PermissionManager Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Backpack\PermissionManager package.
|
*/

Route::group([
            'prefix' => config('backpack.base.route_prefix', 'admin'),
            'middleware' => ['web', backpack_middleware()],
            'namespace' => 'App\Http\Controllers\Admin',
    ], function () {
        CRUD::resource('permission', '\Backpack\PermissionManager\app\Http\Controllers\PermissionCrudController');
        CRUD::resource('role', '\Backpack\PermissionManager\app\Http\Controllers\RoleCrudController');
        CRUD::resource('user', 'UserCrudController');
    });
