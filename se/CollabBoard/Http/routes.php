<?php

Route::group(['middleware' => 'auth', 'namespace' => 'CollabBoard'], function () {
    Route::group(['prefix' => 'collab/{collabUrl}'], function () {
        Route::post('boards', 'BoardController@store');
        Route::get('boards', 'BoardController@index');
        Route::get('boards/{boardId}', 'BoardController@find');
        Route::put('boards/{boardId}', 'BoardController@update');
        Route::delete('boards/{boardId}', 'BoardController@destroy');
    });

    Route::get('boards', 'AdminBoardController@index');
    Route::post('boards', 'AdminBoardController@store');

    Route::post('boards/{boardId}/productFolders', 'ProductFolderController@store');
    Route::get('boards/{boardId}/productFolders', 'ProductFolderController@index');
    Route::get('boards/{boardId}/productFolders/{productFolderId}', 'ProductFolderController@find');
    Route::put('boards/{boardId}/productFolders/{productFolderId}', 'ProductFolderController@update');
    Route::delete('boards/{boardId}/productFolders/{productFolderId}', 'ProductFolderController@destroy');
    Route::post('productFolders/{productFolderId}/comments', 'ProductFolderController@addComment');
    Route::get('productFolders/{productFolderId}/comments', 'ProductFolderController@getComments');
    Route::delete('productFolders/{productFolderId}/comments/{commentId}', 'ProductFolderController@deleteComment');

    Route::post('boards/{boardId}/picks', 'PickController@store');
    Route::get('boards/{boardId}/picks', 'PickController@index');
    Route::get('boards/{boardId}/picks/{pickId}', 'PickController@find');
    Route::put('boards/{boardId}/picks/{pickId}', 'PickController@update');
    Route::delete('boards/{boardId}/picks/{pickId}', 'PickController@destroy');
    Route::put('picks/{pickId}/favourite', 'PickController@favourite');
    Route::delete('picks/{pickId}/favourite', 'PickController@removeFavourite');
    Route::post('picks/{pickId}/comments', 'PickController@addComment');
    Route::get('picks/{pickId}/comments', 'PickController@getComments');
    Route::delete('picks/{pickId}/comments/{commentId}', 'PickController@deleteComment');
});

Route::group(['namespace' => 'CollabBoard', 'prefix' => 'collab/{collabUrl}'], function () {
    Route::get('invite/{inviteCode}/user', 'CollabUserController@getInvitedUser');
    Route::post('invite/{inviteCode}/user', 'CollabUserController@legacySignup');
    Route::get('invite/{inviteCode}/google/{token}', 'CollabUserController@googleSignup');
});
