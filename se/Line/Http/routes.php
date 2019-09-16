<?php

Route::group(['middleware' => 'auth'], function () {
    Route::get('lines/filter', 'LineController@filter');
	Route::post('lines', 'LineController@store');
	Route::delete('lines/{lineId}', 'LineController@destroy');
	Route::put('lines/{lineId}/complete', 'LineController@completeLine');
	Route::put('lines/{lineId}/undo', 'LineController@undoLine');
	Route::put('lines/{lineId}/rollback', 'LineController@rollbackLine');

	Route::get('lines/meta', 'LineController@getAllMeta');
	Route::put('lines/{lineId}', 'LineController@update');
	Route::get('lines/{lineId}', 'LineController@show');
	Route::post('lines/{lineId}/vlpApproval', 'LineController@approveVLP');
	Route::delete('lines/{lineId}/vlpApproval', 'LineController@disapproveVLP');
	Route::get('lines', 'LineController@index');

	Route::post('lines/{lineId}/styles', 'StyleController@store');
	// Route::get('lines/{lineId}/styles', 'StyleController@index');
	Route::put('lines/{lineId}/styles/{styleId}', 'StyleController@update');

	Route::get('styles/{styleId}/approvalLists', 'StyleController@getApprovalLists');

	Route::post('styles/{styleId}/{approvalName}/{approvalNameId}/check',
			'StyleController@approvedChecklist');

	Route::post('styles/{styleId}/{approvalName}/{approvalNameId}/uncheck',
			'StyleController@unapprovedChecklist');

	Route::delete('lines/{lineId}/styles/{styleId}', 'StyleController@destroy');
	Route::put('lines/{lineId}/styles/{styleId}/complete', 'StyleController@completeStyle');
	Route::put('lines/{lineId}/styles/{styleId}/undo', 'StyleController@undoStyle');
	Route::put('lines/{lineId}/styles/{styleId}/rollback', 'StyleController@rollbackStyle');
	Route::post('lines/{lineId}/styles/{styleId}/samples', 'StyleController@addSample');
});
