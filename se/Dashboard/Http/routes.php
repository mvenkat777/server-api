<?php

Route::group(['middleware' => 'auth'], function () {
     Route::get('execDashboard','LineController@salesStream');
    Route::get('stream/activity','DashboardController@showActivity');
    Route::get('stream/notifications','DashboardController@showNotification');
    Route::put('stream/notifications/{notificationId}','DashboardController@updateNotification');
    Route::get('stream/productStream','DashboardController@productStream');

    Route::get('stream/notificationFeed', 'DashboardController@getNotificationFeed');
    Route::get('stream/notificationFeed/{id}', 'DashboardController@getEntityNotificationFeed');
    
    Route::get('stream/{app}','DashboardController@appFeed');
    /*
    Route::get('stream/tasks','DashboardController@');
    Route::get('stream/techpack','DashboardController@');
    Route::get('stream/tna','DashboardController@');
    Route::get('stream/sample','DashboardController@');
     */
});
