<?php
Route::group(['middleware' => 'auth'], function () 
{
    Route::get('appslist', 'HelpController@getAllApp'); 
    Route::post('help', 'HelpController@store'); 
    Route::get('help', 'HelpController@index'); 
    Route::get('help/{slug}', 'HelpController@show');
    Route::put('help/{slug}', 'HelpController@update');
    Route::post('help/{slug}/like','HelpController@like');
    Route::post('help/{slug}/dislike', 'HelpController@dislike');
    Route::post('help/{slug}/feedback', 'HelpController@feedbackStore');
    Route::delete('help/delete/{slug}', 'HelpController@deleteHelp');
    Route::get('help/topic/{appid}', 'HelpController@getTopicByAppId');
});

