<?php

Route::group(['middleware' => 'auth'], function () {
	Route::post('management/customers', 'CustomerController@store');
    Route::get('management/customers', 'CustomerController@index');
    Route::get('management/customers/filter', 'CustomerController@filter');
    Route::get('management/customers/schema', 'CustomerController@getSchema');
    Route::get('management/customers/{id}', 'CustomerController@show');
    Route::put('management/customers/{id}', 'CustomerController@update');
    Route::post('management/customers/{id}/partners', 'CustomerController@addPartners');
    Route::post('management/customers/{id}/brands', 'CustomerController@addBrands');
    Route::delete('management/customers/{id}', 'CustomerController@destroy');
    Route::delete('management/customers/{customerId}/contacts/{contactId}', 'CustomerController@deleteContact');
    Route::delete('management/customers/{customerId}/partners/{partnerid}', 'CustomerController@deletePartner');
    Route::delete('management/customers/{customerId}/addresses/{addressId}', 'CustomerController@deleteAddress');
    Route::delete('management/customers/{customerId}/brands/{brandId}', 'CustomerController@deleteBrand');
    Route::put('management/customers/{customerId}/archive', 'CustomerController@archiveCustomer');

    // collab routes
    Route::post('customers/{customerId}/collab.activate', 'CustomerController@activateCollab');
    Route::get('customers/{customerId}/collab', 'CustomerController@getCollab');
    Route::put('customers/{customerId}/collab', 'CustomerController@updateCollab');

    Route::post('customers/{customerId}/users', 'CustomerController@addUsers');
    Route::get('customers/{customerId}/users', 'CustomerController@getUsers');
    Route::post('customers/{customerId}/collab.invite', 'CollabBoardController@inviteUser');

    Route::get('management/customers/{customerId}/getLines', 'CustomerController@getCustomerLinesHavingTna');
});

