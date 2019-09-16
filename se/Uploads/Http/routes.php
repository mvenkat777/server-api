<?php

Route::group(['middleware' => 'auth'], function () {
	Route::group(array('prefix' => 'uploads'), function () {
        Route::get('/', 'UploadController@getUpload');
        Route::post('/', 'UploadController@postUpload');
        Route::put('{id}/public', 'UploadController@setPublic');
        Route::get('public', 'UploadController@getPublic');
        Route::get('download', 'UploadController@download');
        Route::delete('{id}', 'UploadController@deleteFile');
    });
});