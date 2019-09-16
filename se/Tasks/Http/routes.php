<?php

Route::group(['middleware' => 'auth'], function () {
	Route::get('tasks/filter', 'TasksController@filterTasks');
    Route::get('tasks/categories', 'TasksController@getSchema');
    Route::get('tasks/taskStatus', 'TasksController@getTaskStatus');
    Route::post('tasks/status/{status}', 'TasksController@changeStatusForMultipleTasks');
    Route::post('tasks', 'TasksController@store');
    Route::get('tasks', 'TasksController@index');
    Route::get('tasks/archived', 'TasksController@getAllArchivedTasksByType');
    Route::post('uploadTasks', 'TasksController@uploadTasks');
    Route::post('tasks/reassignMultiple', 'TasksController@reassignMultipleTasks');
    Route::group(['prefix' => 'tasks/{taskId}', 'middleware' => 'taskExist'], function () {
        Route::get('/', 'TasksController@show');
        Route::put('/', 'TasksController@update');
        Route::put('/rollback', 'TasksController@rollback');
        Route::delete('/', 'TasksController@destroy');
        Route::post('status/{status}', 'TasksController@changeTask');
        // Route::post('changePriority', 'TasksController@changePriority');//not needed
        Route::get('seeTask', 'TasksController@seeTask');//can we includ it on status/see
        Route::post('tags', 'TasksController@addTag');
        Route::delete('tags/{tagId}', 'TasksController@removeTag');
        Route::post('attachments', 'TasksController@addAttachment');
        Route::delete('attachments/{attachId}', 'TasksController@deleteAttachment');
        Route::get('comments', 'TasksController@getComments');
        Route::post('comments', 'TasksController@addComment');
        Route::delete('comments/{commentId}', 'TasksController@deleteComment');
        Route::post('follow', 'TasksController@followTask');
        Route::delete('follow/{followerId}', 'TasksController@deleteTaskFollower');
        Route::post('reassign', 'TasksController@reassignTask');
        Route::get('sendMail', 'TasksController@sendMailForTaskOwner');
    });
});
