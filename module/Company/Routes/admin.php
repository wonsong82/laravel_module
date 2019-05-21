<?php
Route::group([
    'prefix' => trim(config('backpack.base.route_prefix', 'admin') . '/company', '/'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace' => '\Module\Company\Controllers\Admin',
    'as' => 'company::'
], function(){

    CRUD::resource('company', 'CompanyCrudController');
    //CRUD::resource('{company}/user', 'CompanyUserCrudController');

    CRUD::resource('user', 'CompanyUserCrudController');
    CRUD::resource('role', 'RoleCrudController');


    //CRUD::resource('offday', 'OffdayMonthCrudController');
    //CRUD::resource('offday/{month}/days', 'OffdayDayCrudController');


    CRUD::resource('currency', 'CurrencyCrudController')->with(function(){
        Route::group(['prefix' => 'currency'], function(){
            Route::get('{currency}/activate', 'CurrencyCrudController@activate')->name('crud.currency.activate');
            Route::get('{currency}/deactivate', 'CurrencyCrudController@deactivate')->name('crud.currency.deactivate');
        });
    });


    CRUD::resource('payterm', 'PaytermCrudController')->with(function(){
        Route::group(['prefix' => 'payterm'], function(){
            Route::get('{payterm}/activate', 'PaytermCrudController@activate')->name('crud.payterm.activate');
            Route::get('{payterm}/deactivate', 'PaytermCrudController@deactivate')->name('crud.payterm.deactivate');
        });
    });



    CRUD::resource('unit', 'UnitCrudController')->with(function(){
        Route::group(['prefix' => 'unit'], function(){
            Route::get('{unit}/activate', 'UnitCrudController@activate')->name('crud.unit.activate');
            Route::get('{unit}/deactivate', 'UnitCrudController@deactivate')->name('crud.unit.deactivate');
        });
    });


    CRUD::resource('margin-rate', 'MarginRateCrudController');


});


