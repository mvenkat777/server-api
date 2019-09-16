<?php

Route::group(['middleware' => 'auth'], function () {
	Route::resource('priorities', 'PriorityController', ['except' => ['destroy']]);
});