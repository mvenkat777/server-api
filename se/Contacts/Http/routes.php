<?php

Route::group(['middleware' => 'auth'], function () {
	Route::resource('contacts','ContactController');
	Route::get('users/{userId}/contacts/{contactId}','ContactController@userContact');
});