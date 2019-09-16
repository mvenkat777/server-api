<?php

Route::group(['middleware' => 'auth'], function () {
	Route::group(['middleware' => 'check.god'], function () {
        Route::resource('groups', 'GroupController');
    });
});