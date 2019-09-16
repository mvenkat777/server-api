<?php

Route::post('auth', 'AuthController@legacy');
Route::get('/auth/google/{token}', 'AuthController@google');
Route::get('/auth/facebook/{token}', 'AuthController@facebook');
Route::get('auth/verify/{confirmationCode?}', 'AuthController@verifyAccount');
Route::post('auth/sendResetPassword', 'AuthController@sendResetPasswordLink');
Route::post('auth/resetPassword', 'AuthController@resetPassword');
Route::post('auth/check', 'AuthController@checkLoginStatus');

Route::group(['middleware' => 'auth'], function () {
	Route::post('auth/changePassword', 'AuthController@changePassword');
    Route::put('auth/password', 'AuthController@updatePassword');
    Route::get('auth/logout', 'AuthController@getLogout');
    Route::get('auth/user', 'AuthController@getUserByToken');

    /**
     * Routes for user Online/Offline Status
     */
    Route::post('user/status', 'AuthController@userStatus');
});
