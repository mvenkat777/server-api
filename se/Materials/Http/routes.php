<?php

Route::group(['middleware' => 'auth'], function () {

	Route::get('materials/filter', 'MaterialController@filter');
	Route::post('materials/createnew', 'MaterialController@store');
    Route::get('materials/', 'MaterialController@index');
    Route::get('materials/{materialId}', 'MaterialController@show');
    Route::put('materials/update/{materialId}', 'MaterialController@update');

    Route::get('materials/library/countries', 'MaterialController@getVendorCountries');
    Route::get('materials/library/filter', 'MaterialController@filterLibrary');
    Route::post('materials/library/createnew', 'MaterialController@storeLibrary');
    Route::put('materials/library/update/{libraryId}', 'MaterialController@updateLibrary');
    Route::get('materials/library/fabricreferences', 'MaterialController@getFabricReference');   
    Route::post('materials/library/print', 'MaterialController@materialLibraryPrint');
});
