<?php


Route::post('users', 'UserController@store');

Route::group(['middleware' => 'auth'], function () {
	Route::post('management/users', 'UserController@managementCreateUser');

	Route::get('users/search', 'UserController@search');
    Route::post('users/ban', 'UserController@banUser');
    Route::post('users/unban', 'UserController@unBannedUser');
    Route::post('users/notes', 'UserController@postNote');
    Route::get('users/notes', 'UserController@getNote');
    Route::put('users/notes/{noteId}', 'UserController@updateNote');
    Route::delete('users/notes/{noteId}', 'UserController@deleteNote');
    Route::post('users/tags', 'UserController@addTag');
    Route::get('users/tags', 'UserController@getAllTag');
    Route::get('users/{userId}/tags', 'UserController@getUserTag');
    Route::delete('users/{userId}/tags/{tagId}', 'UserController@deleteTag');
    Route::get('users', 'UserController@index');
    Route::get('users/{id}', 'UserController@show');
    Route::put('users/{id}', 'UserController@update');
});
