<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('candidateRoute.prefix.api'),
    'namespace' => config('candidateRoute.namespace.api'),
], function () {

    Route::post('register', 'ApiCandidateAuthController@register')->name('register');

    Route::post('verify-opt', 'ApiCandidateAuthController@verifyOtp')->name('verifyOtp');

    Route::post('password-submit', 'ApiCandidateAuthController@passwordSubmit')->name('passwordSubmit');

    Route::post('login', 'ApiCandidateAuthController@login')->name('login');

    Route::get('logout', 'ApiCandidateAuthController@logout')->name('logout');

    Route::group([
        'middleware' => ['auth:api','candidateMiddleware'],
    ], function(){

        // Route::group([
        //     'prefix' => '',
        //     'as' => 'candidate.'
        // ], function(){

        // });

        
        Route::post('store/{companyid}', 'ApiCandidateController@store');

        Route::get('get-candidates/{companyid}', 'ApiCandidateController@getCandidatesByCompany');

        Route::get('all-leaves/{companyid}','ApiCandidateLeaveController@allCandidateLeave');

        Route::get('store-leave/{companyid}','ApiCandidateLeaveController@storeCandidateLeave');

        Route::get('update-leave/{companyid}/{leave_id}','ApiCandidateLeaveController@updateCandidateLeave');

        Route::get('delete-leave/{companyid}/{leave_id}','ApiCandidateLeaveController@deleteCandidateLeave');

        Route::get('leave-types','ApiCandidateLeaveController@getLeaveTypes');

            
        Route::group([
            'prefix' => '/invitation',
          
        ], function(){
            Route::get('all','ApiCandidateInvitationController@allCandidateInvitations');

            Route::post('invitation-update/{invitation_id}','ApiCandidateInvitationController@updateCandidateInvitation');
        });

    });


});
