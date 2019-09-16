<?php
Route::group(array('prefix' => 'log'), function()
{
	Route::get('/','LogController@getAllLogs');

	Route::get('userId/{userId}/fromDate/{fromDate}','LogController@getLogsByUserId');

	Route::get('byDate/{date}','LogController@getLogsByCreatedDate');
	
	Route::get('fromDate/{fromdate}/tillDate/{tillDate}','LogController@getLogsFromDateToTillDate');

	Route::get('requestType/{requestType}','LogController@getLogsByRequestType');

	Route::get('responseStatus/{responseStatus}','LogController@getByResponseStatus');
});

Route::group(['prefix' => 'tasks', 'middleware' => 'auth'], function(){

	Route::get('{taskId}/send','TasksController@sendMailForTaskOwner');

	Route::get('{taskId}/activity','TasksController@getTaskActivity');

	// Route::get('{taskId}/comment/send','TasksController@sendMailWithAttachements');

	// Route::get('{taskId}/comment/attachement/send','TasksController@sendMailWithAttachementsAndComments');
});

Route::group(['prefix' => 'tna', 'middleware' => 'auth'], function(){

	Route::get('{tnaId}/activity','TNAController@getTNAActivity');
});

Route::group(['prefix' => 'techpack', 'middleware' => 'auth'], function(){

	Route::get('{techpackId}/activity','TechpackController@getTechpackActivity');
});

Route::group(['prefix' => 'sample', 'middleware' => 'auth'], function(){

	Route::get('{sampleId}/activity','SampleSubmissionController@getSampleActivity');
});

Route::group(['middleware' => 'auth'], function () {
	Route::group(array('prefix' => 'tasks'), function(){

		Route::get('{taskId}/send','TasksController@sendMailForTaskOwner');

		Route::get('{taskId}/activity','TasksController@getTaskActivity');

		// Route::get('{taskId}/comment/send','TasksController@sendMailWithAttachements');

		// Route::get('{taskId}/comment/attachement/send','TasksController@sendMailWithAttachementsAndComments');
	});
	
	Route::group(array('prefix' => 'rules'), function(){
		Route::post('entity','BusinessRuleController@storeNewEntity');
		Route::post('entity/rule','BusinessRuleController@storeNewRule');
		Route::get('/','BusinessRuleController@getAllRule');
		Route::put('entity/{id}','BusinessRuleController@updateCategory');
		Route::put('entity/rule/{id}','BusinessRuleController@updateRule');
		Route::delete('entity/{id}','BusinessRuleController@deleteEntity');
		Route::delete('rule/{ruleId}','BusinessRuleController@deleteRule');
	});
});
