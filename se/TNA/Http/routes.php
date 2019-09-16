<?php

Route::group(['middleware' => 'auth'], function () {
	Route::get('tna/meta', 'TNAController@getMetaData');
	Route::get('tna/templates', 'TNAController@getTemplateList');
	Route::delete('tna/templates/{templateId}', 'TNAController@deleteTemplate');
    Route::post('tna', 'TNAController@store');
    Route::get('tna', 'TNAController@index');
    Route::get('tna/filter', 'TNAController@filter');
    Route::group(['middleware' => 'tna'], function () {
        Route::group(['prefix' => 'tna/{tid}'], function () {
            Route::get('/', 'TNAController@show');
            Route::put('/', 'TNAController@update');
            Route::put('/rollback', 'TNAController@rollback');
            Route::delete('/', 'TNAController@destroy');
            Route::post('/sync', 'TNAController@syncTNA');
            Route::post('/attachments', 'TNAController@addAttachment');
            Route::delete('/attachments', 'TNAController@deleteAttachment');
            Route::post('state/{state}', 'TNAController@changeState');

            Route::post('items', 'TNAItemController@store');
            Route::get('items', 'TNAItemController@index');
            Route::group(['middleware' => 'tnaItem'], function () {
                Route::get('items/{tnaItemId}', 'TNAItemController@show');
                Route::put('items/{tnaItemId}', 'TNAItemController@update');
                Route::delete('items/{tnaItemId}', 'TNAItemController@destroy');
                Route::post('items/{tnaItemId}/publish', 'TNAItemController@publishTask');
            });
        });
    });
});
