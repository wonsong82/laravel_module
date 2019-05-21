<?php


Route::group([
    'prefix' => trim(config('backpack.base.route_prefix', 'admin') . '/application', '/'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace' => '\Module\Application\Controllers\Admin',
    'as' => 'application::'
], function(){

    CRUD::resource('locale', 'LocaleCrudController');

    CRUD::resource('log', 'ActivityLogCrudController');

    CRUD::resource('user', 'UserCrudController');
    CRUD::resource('role', 'RoleCrudController');
    CRUD::resource('permission', 'PermissionCrudController');



});