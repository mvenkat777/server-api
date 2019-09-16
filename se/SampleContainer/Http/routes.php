<?php

Route::group(['middleware' => 'auth', 'prefix' => 'sampleContainers'], function () {
    // Sample Container Routes
    Route::post('', 'SampleContainerController@store');
    Route::get('{sampleContainerId}', 'SampleContainerController@find');
    Route::get('', 'SampleContainerController@index');
    Route::delete('{sampleContainerId}', 'SampleContainerController@destroy');
    Route::put('{sampleContainerId}/rollback', 'SampleContainerController@rollbackSampleContainer');
    Route::put('{sampleContainerId}/complete', 'SampleContainerController@completeSampleContainer');
    Route::put('{sampleContainerId}/undo', 'SampleContainerController@undoSampleContainer');

    // Sample Routes
    Route::post('{sampleContainerId}/samples', 'SampleController@store');
    Route::get('{sampleContainerId}/samples/{sampleId}', 'SampleController@find');
    Route::put('{sampleContainerId}/samples/{sampleId}', 'SampleController@update');
    Route::put('{sampleContainerId}/samples/{sampleId}/complete', 'SampleController@completeSample');
    Route::put('{sampleContainerId}/samples/{sampleId}/undo', 'SampleController@undoSample');
    Route::delete('{sampleContainerId}/samples/{sampleId}', 'SampleController@destroy');
    Route::get('{sampleContainerId}/samples/{sampleId}/exportPOM', 'SampleController@exportPOM');
    Route::get('{sampleContainerId}/samples/{sampleId}/export', 'SampleController@export');
    Route::put('{sampleContainerId}/samples/{sampleId}/rollback', 'SampleController@rollbackSample');
    Route::get('{sampleContainerId}/pom/{sampleId}', 'SampleContainerController@getTechpackPOM');
});

Route::group(['middleware' => 'auth'], function () {
    // Sample Criteria Routes
    Route::post('sample/{sampleId}/criterias', 'SampleCriteriaController@store');
    Route::get('sample/{sampleId}/criterias/{criteriaId}', 'SampleCriteriaController@find');
    Route::put('sample/{sampleId}/criterias/{criteriaId}', 'SampleCriteriaController@update');
    Route::delete('sample/{sampleId}/criterias/{criteriaId}', 'SampleCriteriaController@destroy');

    // Sample Criteria Attachment Routes
    Route::post('sampleCriterias/{criteriaId}/attachments', 'SampleCriteriaAttachmentController@store');
    Route::delete('sampleCriterias/{criteriaId}/attachments/{attachmentId}', 'SampleCriteriaAttachmentController@destroy');

    // Sample Criteria Comments Routes
    Route::post('sampleCriterias/{criteriaId}/comments', 'SampleCriteriaCommentController@store');
    Route::delete('sampleCriterias/{criteriaId}/comments/{commentId}', 'SampleCriteriaCommentController@destroy');
});
