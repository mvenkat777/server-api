<?php

Route::group(['middleware' => 'auth'], function () {
	Route::post('users/{userId}/address', 'AddressController@store');
    Route::get('users/{userId}/address', 'AddressController@show');
    Route::post('users/{userId}/address/{id}/update', 'AddressController@update');
    Route::get('users/{userId}/address/{id}/destroy', 'AddressController@destroy');
    Route::get('users/{userId}/address/destroy', 'AddressController@destroyAll');
});