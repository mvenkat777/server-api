<?php

Route::group(['middleware' => 'auth'], function () {
	Route::resource('orgs', 'OrgController');
});