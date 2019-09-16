<?php

Route::group(['middleware' => 'auth'], function () {
	Route::get('techpacks/schema', 'TechpackController@getSchema');
    Route::post('techpacks/{techpacks}/associations', 'TechpackController@associations');
    Route::put('techpacks/{techpacks}/complete', 'TechpackController@completeTechpack');
    Route::put('techpacks/{techpacks}/undo', 'TechpackController@undoTechpack');
    Route::post('techpacks/schema', 'TechpackController@generateSchema');
    Route::get('techpacks/meta', 'TechpackController@getMeta');
    Route::get('techpacks/filter', 'TechpackController@filter');
    Route::resource('techpacks', 'TechpackController', ['except' => ['create', 'edit','show']]);
    Route::put('techpacks/{techpackId}/rollback', 'TechpackController@rollback');
    Route::put('techpacks/{techpackId}/lock', 'TechpackController@lock');
    Route::put('techpacks/{techpackId}/unlock', 'TechpackController@unlock');

    Route::get('techpacks/{techpackId}/sample', 'TechpackController@getTechpackSample');
    Route::get('techpacks/{techpackId}/tna', 'TechpackController@getTechpackTNA');
    Route::get('techpacks/{techpackId}/relations', 'TechpackController@getTechpackRelatedData');

    // Techpack Exports
    Route::get('techpacks/{techpackId}/exports/vendor', 'TechpackExportsController@vendorExport');
    Route::post('techpacks/exports/multiple', 'TechpackExportsController@multipleExport');
    Route::post('techpacks/{techpackId}/exports/selective', 'TechpackExportsController@selectiveExport');

    //techpack sharing
    Route::post('techpacks/{techpackId}/share', [
        'as' => 'sharedTechpack',
        'uses' => 'TechpackController@share'
    ]);

    Route::get('techpacks/{techpackId}', [
        'as' => 'getSharedTechpack',
        'uses' => 'TechpackController@show'
    ]);

    // colorways endpoints
    Route::post('techpacks/{techpackId}/colorways', 'ColorwaysController@store');
    Route::get('techpacks/{techpackId}/colorways', 'ColorwaysController@index');
    Route::post('techpacks/{techpackId}/colorways/bulksave', 'ColorwaysController@bulkStore');
    Route::delete('techpacks/{techpackId}/colorways/{colorwayId}', 'ColorwaysController@destroy');

    Route::post('techpacks/{techpackId}/comments', 'TechpackCommentsController@store');
    Route::get('techpacks/{techpackId}/comments', 'TechpackCommentsController@index');
    Route::post('techpacks/{techpackId}/clone', 'TechpackController@cloneTechpack');

    Route::post('techpacks/{techpackId}/cutTickets/comments', 'CutTicketCommentController@store');
    Route::get('techpacks/{techpackId}/cutTickets/comments', 'CutTicketCommentController@index');
    Route::delete('techpacks/{techpackId}/cutTickets/comments/{commentId}', 'CutTicketCommentController@destroy');
});
