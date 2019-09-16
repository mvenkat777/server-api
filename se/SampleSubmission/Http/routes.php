<?php

Route::group(['middleware' => 'auth'], function () {
    Route::post('samples', 'SampleSubmissionController@store');
    Route::get('samples', 'SampleSubmissionController@index');
    Route::get('samples/filter', 'SampleSubmissionController@filter');
    Route::get('samples/{sampleId}', 'SampleSubmissionController@find');
    Route::put('samples/{sampleId}', 'SampleSubmissionController@update');
    Route::delete('samples/{sampleId}', 'SampleSubmissionController@destroy');

    Route::post('samples/{sampleId}/categories', 'SampleSubmissionCategoryController@store');
    Route::get('samples/{sampleId}/categories/{categoryId}', 'SampleSubmissionCategoryController@find');
    Route::put('samples/{sampleId}/categories/{categoryId}', 'SampleSubmissionCategoryController@update');
    Route::delete('samples/{sampleId}/categories/{categoryId}', 'SampleSubmissionCategoryController@destroy');

    Route::post('samples/{sampleId}/categories/{categoryId}/comments', 'SampleSubmissionCommentController@store');
    Route::get('samples/{sampleId}/categories/{categoryId}/comments', 'SampleSubmissionCommentController@index');
    Route::delete(
        'samples/{sampleId}/categories/{categoryId}/comments/{commentId}',
        'SampleSubmissionCommentController@destroy'
    );

    Route::post(
        'samples/{sampleId}/categories/{categoryId}/attachments',
        'SampleSubmissionAttachmentController@store'
    );
    Route::get(
        'samples/{sampleId}/categories/{categoryId}/attachments',
        'SampleSubmissionAttachmentController@index'
    );
    Route::delete(
        'samples/{sampleId}/categories/{categoryId}/attachments/{attachmentId}',
        'SampleSubmissionAttachmentController@destroy'
    );

});
