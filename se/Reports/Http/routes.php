<?php

Route::group(['middleware' => 'auth'], function () {
	Route::post('reports/{entity}', 'ReportController@index');
});