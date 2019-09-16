<?php

Route::group(['middleware' => 'auth'], function () {
	Route::get('shipments/filter', 'ShipmentController@filter');
	Route::resource('shipments', 'ShipmentController');
});