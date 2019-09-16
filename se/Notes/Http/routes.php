<?php

Route::group(['middleware' => 'auth'], function () {
	Route::get('notes', 'NoteController@index');
    Route::post('notes', 'NoteController@store');
    Route::get('notes/shared', 'NoteController@getAllSharedNote');
    Route::get('notes/{noteId}', 'NoteController@show');
    Route::put('notes/{noteId}', 'NoteController@update');
    Route::delete('notes/{noteId}', 'NoteController@destroy');
    Route::post('notes/{noteId}/share', 'NoteController@shareNote');
    Route::post('notes/{noteId}/comment','NoteController@addComment');
    Route::put('notes/{noteId}/comment/{commentId}','NoteController@updateComment');
    Route::delete('notes/{noteId}/comment/{commentId}','NoteController@deleteComment');
});