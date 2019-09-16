<?php

Route::group(['middleware' => 'auth'], function () {
	Route::group(array('prefix' => 'payments'), function () {

        Route::post('/', 'PaymentController@store');

        Route::get('{id}/cancel', 'PaymentController@destroy');

        Route::get('get/{get?}', 'PaymentController@getAllOrders');

        Route::get('filter', 'PaymentController@filter');
    });
});