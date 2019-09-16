<?php

Route::group(['middleware' => 'auth'], function () {
    Route::get('locations', 'LocationController@index');
    Route::post('locations', 'LocationController@store');
    Route::get('holidays', 'HolidayController@index');
    Route::get('holidays/{userId}', 'HolidayController@getListOfUser');
    Route::group(['prefix' => 'locations/{locationId}'], function () {
        Route::put('/', 'LocationController@update');
        Route::get('/', 'LocationController@show');
        Route::delete('/', 'LocationController@destroy');

        Route::get('/holidays', 'HolidayController@getByLocation');
        Route::post('/holidays', 'HolidayController@store');
        Route::get('/holidays/{year}', 'HolidayController@getByYear');
        Route::put('/holidays/{holidayId}', 'HolidayController@update');
        Route::get('/holidays/{holidayId}', 'HolidayController@show');
        Route::delete('/holidays/{holidayId}', 'HolidayController@destroy');
    });
});
