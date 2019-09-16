<?php
/**
 * Routes for Form App
 */
Route::group(['middleware' => 'auth', 'prefix' => 'form'], function () {
    
    Route::get('/', 'FormController@getForm');
    Route::get('history', 'FormController@getHistory');
    Route::get('meta', 'FormController@getFormMeta');
    Route::post('/', 'FormController@store');
    Route::put('/', 'FormController@update');    
    Route::get('allStatus', 'FormController@index');
    Route::get('/status/{type}' , 'FormController@getFormsByStatus');
    Route::delete('/', 'FormController@destroy');
});