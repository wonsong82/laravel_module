<?php
Route::group(
    [
        'namespace'  => '\Module\Application\Controllers\Admin',
        'middleware' => ['web', 'locale'],
        'prefix'     => config('backpack.base.route_prefix'),
    ],
    function () {
        // setup the auth routes

        // Authentication Routes...
        Route::get('login', 'Auth\LoginController@showLoginForm')->name('backpack.auth.login');
        Route::post('login', 'Auth\LoginController@login');
        Route::get('logout', 'Auth\LoginController@logout')->name('backpack.auth.logout');
        Route::post('logout', 'Auth\LoginController@logout');

        // Registration Routes...
        Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('backpack.auth.register');
        Route::post('register', 'Auth\RegisterController@register');

        // Password Reset Routes...
        Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('backpack.auth.password.reset');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset');
        Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('backpack.auth.password.reset.token');
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('backpack.auth.password.email');


        // setup the dashboard routes

        Route::get('dashboard', 'AdminController@dashboard')->name('backpack.dashboard');
        Route::get('/', 'AdminController@redirect')->name('backpack');


        // setup the "my account" routes

        Route::get('edit-account-info', 'Auth\MyAccountController@getAccountInfoForm')->name('backpack.account.info');
        Route::post('edit-account-info', 'Auth\MyAccountController@postAccountInfoForm');
        Route::get('change-password', 'Auth\MyAccountController@getChangePasswordForm')->name('backpack.account.password');
        Route::post('change-password', 'Auth\MyAccountController@postChangePasswordForm');

    });