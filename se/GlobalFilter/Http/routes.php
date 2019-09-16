<?php
Route::group(['middleware' => 'auth'], function () 
{
    Route::get('globalfilter', 'GlobalFilterController@index'); 
    Route::post('globalfilter', 'GlobalFilterController@show'); 

    Route::get('getAllApps', 'GlobalFilterController@getAllApps');
    
});

