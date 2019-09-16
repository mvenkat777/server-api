<?php

Route::group(['middleware' => 'auth', 'prefix' => 'collab'], function () {

	/* Request for Collab */
	Route::get('/','CollabController@getCollab');
	Route::post('/','CollabController@storeCollab');
	Route::get('{collabId}/members','CollabController@getCollabMembers');
	Route::put('{collabId}/member/{userId}/manager/assign','CollabController@assignAsManager');
	Route::put('{collabId}/member/{userId}/manager/remove','CollabController@removeAsManager');
	Route::put('{collabId}/member/add','CollabController@addNewMember');
	Route::put('{collabId}/member/remove','CollabController@removeNewMember');

	/* Request for card */
	Route::post('{collabId}/card','CollabController@storeCard');
	Route::get('{collabId}/card','CollabController@getCard');
	Route::put('{collabId}/card','CollabController@updateCollabCard');
	Route::get('{collabId}/card/{cardId}','CollabController@getCardById');

	/* Request To store update or archive a comment  */
	Route::get('{collabId}/card/{cardId}/comment','CollabController@getComment');
	Route::post('{collabId}/card/{cardId}/comment','CollabController@storeComment');
	Route::put('{collabId}/card/{cardId}/comment/{commentId}','CollabController@updateComment');
	Route::delete('{collabId}/card/{cardId}/comment/{commentId}','CollabController@archiveComment');

	/* Request To store update or archive a comment  */
	Route::get('{collabId}/card/{cardId}/comment/{commentId}/reply','CollabController@getReply');
	Route::post('{collabId}/card/{cardId}/comment/{commentId}/reply','CollabController@storeReply');
	Route::put('{collabId}/card/{cardId}/comment/{commentId}/reply/{replyId}','CollabController@updateReply');
	Route::delete('{collabId}/card/{cardId}/comment/{commentId}/reply/{replyId}','CollabController@archiveReply');

	/* Request Routes for Direct Messages */
	Route::get('message','CollabController@getConvUserHistory');
	Route::get('message/{chatId}','CollabController@getMessageByChatId');
	Route::post('message/user','CollabController@getUserSpecificConv');
	Route::post('message','CollabController@storeUserSpecificConv');
	Route::put('message','CollabController@updateDirectMessage');
	Route::post('message/share','CollabController@shareMessage');
	Route::get('message/{chatId}/card/{messageId}','CollabController@getMessageById');

	/* Request Routes for Archiving */
	Route::delete('{collabId}','CollabController@archiveCollab');
	Route::delete('{chatId}/message/{messageId}','CollabController@archiveMessage');
	Route::delete('{collabId}/card/{cardId}','CollabController@archiveCard');

	/* Request To join or invite group */
	Route::get('{collabId}/card/{cardId}/invite','CollabController@inviteUser');

	/* Request To check user status whether online or offline  */
	Route::get('user/status','CollabController@UpdateUserState');
	// Route::get('message/status/{chatId}','CollabController@UpdateUserSeenState');

	/* Request for group message  */
	Route::post('group','CollabController@storeGroupChat');

	/* Request To shared  */
	Route::get('/SharedByMe','CollabController@getSharedDataByme');
	Route::get('/SharedWithMe','CollabController@getSharedDataWithme');

});
