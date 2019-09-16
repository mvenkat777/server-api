<?php

Route::group(['middleware' => 'auth','prefix' => 'pom'], function () {

    Route::post('categories', 'CategoriesController@store');
    Route::get('categories', 'CategoriesController@index');
    Route::put('categories/{code}', 'CategoriesController@update');
    Route::delete('categories/{code}', 'CategoriesController@destroy');

    Route::post('products', 'ProductsController@store');
    Route::get('products', 'ProductsController@index');
    Route::put('products/{code}', 'ProductsController@update');
    Route::delete('products/{code}', 'ProductsController@destroy');

    Route::post('producttypes', 'ProductTypeController@store');
    Route::get('producttypes', 'ProductTypeController@index');
    Route::put('producttypes/{code}', 'ProductTypeController@update');
    Route::delete('producttypes/{code}', 'ProductTypeController@destroy');

    Route::post('products/{productCode}/producttypes/{productTypeCode}', 'ProductsController@attachProductType');
    Route::delete('products/{productCode}/producttypes/{productTypeCode}', 'ProductsController@detachProductType');
    Route::get('list', 'ProductsController@listPom');
    

    Route::post('categories/{categoryCode}/products/{productCode}', 'CategoriesController@attachProduct');
    Route::delete('categories/{categoryCode}/products/{productCode}', 'CategoriesController@detachProduct');
    // Route::get('list', 'CategoriesController@listPom');
    
    Route::post('classifications', 'ClassificationController@store');
    Route::get('classifications', 'ClassificationController@index');
    Route::put('classifications/{code}', 'ClassificationController@update');
    Route::delete('classifications/{code}', 'ClassificationController@destroy');

    Route::post('sizes', 'SizeController@store');
    Route::get('sizes', 'SizeController@index');
    Route::put('sizes/{code}', 'SizeController@update');
    Route::delete('sizes/{code}', 'SizeController@destroy');

    Route::post('sizetypes', 'SizeTypeController@store');
    Route::get('sizetypes', 'SizeTypeController@index');
    Route::put('sizetypes/{code}', 'SizeTypeController@update');
    Route::delete('sizetypes/{code}', 'SizeTypeController@destroy');

    Route::post('sizeranges', 'SizeRangeController@store');
    Route::get('sizeranges', 'SizeRangeController@index');
    Route::get('sizeranges/{range}', 'SizeRangeController@getByRange');
    Route::put('sizeranges/{code}', 'SizeRangeController@update');
    Route::delete('sizeranges/{code}', 'SizeRangeController@destroy');

    Route::post('/', 'PomController@store');
    Route::post('{pomId}/sheetrows', 'PomController@addSheetRow');
    Route::put('{pomId}/sheetrows/{sheetId}/rollback', 'PomController@rollbackSheetRow');
    Route::get('check', 'PomController@checkPom');
    Route::put('{pomId}/sheetrows', 'PomController@updateSheetRow');
    Route::delete('{pomId}/sheetrows/{sheetId}', 'PomController@destroySheetRow');
    Route::get('/', 'PomController@index');
    Route::get('techpack', 'PomController@getForTechpack');
    Route::get('sheetrows/filter', 'PomController@filter');
    Route::put('{pomId}', 'PomController@update');
    Route::delete('{pomId}', 'PomController@destroy');
    Route::get('{pomId}', 'PomController@show');
    Route::put('{pomId}/rollback', 'PomController@rollbackPom');
});
