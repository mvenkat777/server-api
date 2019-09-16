<?php

Route::group(['middleware' => 'auth'], function () {
	Route::group(array('prefix' => 'uploads'), function () {
        Route::get('/', 'UploadController@getUpload');
        Route::post('/', 'UploadController@postUpload');
        Route::post('set_public', 'UploadController@setPublic');
        Route::get('public_files', 'UploadController@getPublic');
        Route::get('shared_files', 'UploadController@getShared');
        Route::get('download/{link}', 'UploadController@download');
        Route::get('view/{link}', 'UploadController@view');
        Route::post('delete', 'UploadController@deleteFile');
        Route::get('shared_files/editor', 'UploadController@getEditorLink');
    });
});