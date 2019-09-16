<?php

Route::group(['middleware' => 'auth'], function () {
	Route::group(['middleware' => 'check.god'], function () {
		Route::resource('roles', 'RoleController');
        Route::get('group/{groupId}/roles', 'RoleController@getGroupRoles');
        Route::post('role/{roleId}/users', 'RoleController@attachUsersToRoles');
        Route::get('role/{roleId}/users', 'RoleController@getUsersByRole');
        Route::delete('role/{roleId}/users', 'RoleController@removeUsersByRole');
        Route::get('role/{roleId}/unassignedusers', 'RoleController@getUnassignedUsersByRole');
	});
});