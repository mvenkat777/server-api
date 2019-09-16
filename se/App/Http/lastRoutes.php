<?php

Route::get('test', 'QueueController@index');

Route::group(array('prefix' => 'payments'), function () {

    Route::post('{id}/payment', 'PaymentController@getStatus');

    Route::get('{value?}', 'PaymentController@showLink'); //ALWAYS BE THE LAST LINE OF ROUTE
});
