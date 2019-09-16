<?php

Route::group(['middleware' => 'auth'], function () {
	Route::resource('apps', 'AppController');
	Route::get('permissions', 'AppController@getAllPermissions');
});