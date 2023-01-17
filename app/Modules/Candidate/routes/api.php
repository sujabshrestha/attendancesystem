<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('candidateRoute.prefix.api'),
    'namespace' => config('candidateRoute.namespace.api'),
], function () {


    //candidate opt verification and first register

    Route::post('register', 'ApiCandidateAuthController@register')->name('register');

    Route::post('verify-opt', 'ApiCandidateAuthController@verifyOtp')->name('verifyOtp');

    Route::post('password-submit', 'ApiCandidateAuthController@passwordSubmit')->name('passwordSubmit');

    Route::post('login', 'ApiCandidateAuthController@login')->name('login');


    Route::get('logout', 'ApiCandidateAuthController@logout')->name('logout');

    Route::group([
        'middleware' => 'auth:api',
    ], function(){

        // Route::group([
        //     'prefix' => '',
        //     'as' => 'candidate.'
        // ], function(){

        // });

        Route::post('store/{companyid}', 'ApiCandidateController@store')->name('store');

        Route::get('get-candidates/{companyid}', 'ApiCandidateController@getCandidatesByCompany')->name('getCandidatesByCompany');


        Route::get('all-leave-requests/{companyid}','ApiCandidateLeaveController@allCandidateLeave')->name('allCandidateLeave');

        Route::get('store-leave-requests/{companyid}','ApiCandidateLeaveController@storeCandidateLeave')->name('storeCandidateLeave');

        Route::get('update-leave-requests/{companyid}/{leave_id}','ApiCandidateLeaveController@updateCandidateLeave')->name('updateCandidateLeave');

        Route::get('delete-leave-requests/{companyid}/{leave_id}','ApiCandidateLeaveController@deleteCandidateLeave')->name('deleteCandidateLeave');


    });


});
