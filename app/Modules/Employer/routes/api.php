<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('employerRoute.prefix.api'),

    'namespace' => config('employerRoute.namespace.api'),
], function () {

    //employer opt verification and first register

    Route::post('register', 'ApiEmployerAuthController@register')->name('register');

    Route::post('verify-opt', 'ApiEmployerAuthController@verifyOtp')->name('verifyOtp');

    Route::post('password-submit', 'ApiEmployerAuthController@passwordSubmit')->name('passwordSubmit');

    Route::post('login', 'ApiEmployerAuthController@login')->name('login');




    Route::group([
        'middleware' => ['auth:api', 'employerMiddleware']
    ], function () {

        Route::get('logout', 'ApiEmployerAuthController@logout')->name('logout');

        Route::group([
            'prefix' => 'company',
            'as' => 'company.'
        ], function () {
            Route::get('/all',  'ApiCompanyController@index');

            Route::post('/store',  'ApiCompanyController@store');

            Route::post('/update/{id}',  'ApiCompanyController@update');

            Route::post('/destroy/{id}',  'ApiCompanyController@destroy');

            Route::get('/get-companies/{id}',  'ApiCompanyController@getCompaniesByEmployer');
        });
    });
});
