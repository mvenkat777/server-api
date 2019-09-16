<?php
/**
 * Routes for Direct Messages
 */
Route::group(['middleware' => 'auth', 'prefix' => 'messages'], function () {
	
	Route::post('/', 'DirectMessageController@generateChatId');

	Route::get('{chatId?}', 'DirectMessageController@index');
	Route::post('{chatId}', 'DirectMessageController@store');

    Route::put('{chatId}/seen/{messageId}', 'DirectMessageController@seen');

	Route::put('{chatId}/message/{messageId}', 'DirectMessageController@update');
	
	Route::get('{chatId}/files', 'DirectMessageController@getShared');
});