<?php

Route::group(['middleware' => 'auth'], function () {
	Route::get('management/orders/filter', 'OrderController@filter');
    Route::get('customers/{customerId}/orders', 'OrderController@search');
	Route::resource('management/orders', 'OrderController');
});