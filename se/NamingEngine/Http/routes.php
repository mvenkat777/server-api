<?php

Route::group(['prefix' => 'name'], function () {
    Route::get('customer', 'NamingEngineController@customer');
    Route::get('vendor', 'NamingEngineController@vendor');
    Route::get('line', 'NamingEngineController@line');
    Route::get('style', 'NamingEngineController@style');
});
