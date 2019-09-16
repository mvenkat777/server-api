<?php

Route::group(['middleware' => 'auth'], function () {
	Route::post('management/vendors', 'VendorController@store');
    Route::get('management/vendors', 'VendorController@index');
    Route::get('management/vendors/filter', 'VendorController@filter');
    Route::get('management/vendors/schema', 'VendorController@getSchema');
    Route::get('management/vendors/{id}', 'VendorController@show');
    Route::put('management/vendors/{id}', 'VendorController@update');
    Route::post('management/vendors/{id}/partners', 'VendorController@addPartners');
    Route::post('management/vendors/{id}/banks', 'VendorController@addBanks');
    Route::post('management/vendors/{vendorId}/addresses', 'VendorController@AddOrUpdateAddress');
    Route::delete('management/vendors/{id}', 'VendorController@destroy');
    Route::delete('management/vendors/{vendorId}/contacts/{contactId}', 'VendorController@deleteContact');
    Route::delete('management/vendors/{vendorId}/partners/{partnerid}', 'VendorController@deletePartner');
    Route::delete('management/vendors/{vendorId}/addresses/{addressId}', 'VendorController@deleteAddress');
    Route::delete('management/vendors/{vendorId}/banks/{bankId}', 'VendorController@deleteBank');
    Route::get('countries', 'CountryController@index');
    Route::put('management/vendors/{vendorId}/archive', 'VendorController@archiveVendor');

});