<?php
namespace Platform\Collab\Helpers;

use Carbon\Carbon;
use Platform\Collab\Extractor\UrlExtractor;
use Platform\Collab\Repositories\Repository;

class FrameDirectConversation extends Repository{

	/**
	 * @var array data
	 */
	protected $data = [];

	/**
	 * To Frame the data in a structure to input in DB
	 * @var object value
	 */
	public function designForNewConv($value)
	{
		$this->data = [
			'userId' => $value->initiator,
			'chat' => [
				$value->participant => [
					'participant' => $value->participant,
					'chatId' => $value->initiator.'-'.$value->participant,
					'favourites' => [],
					'lastActivityTime' => Carbon::parse(Carbon::now())->toDateTimeString()
				],
			]
		];
		return $this->data;	
	}

	public function designForNewGroupConv($value)
	{
		$data = $value->participant;
		$value->participant = array_push($data, $value->initiator);
		$chatId = $this->generateUUID();
		$this->data = [
			'group' => [
				$chatId => [
					'participant' => $data,
					'chatId' => $chatId,
					'favourites' => [],
					'lastActivityTime' => Carbon::parse(Carbon::now())->toDateTimeString()
				]
			]
		];
		return $this->data;	
	}

	public function designForNewDirectMessage($value)
	{ 
		if($value->type == 'url')
			$url = (new UrlExtractor)->extract($value->message);
		$this->data = [
				'id' => $this->generateUUID(),
				'message' => $value->message,
				'urlMeta' => isset($url)?$url:Null,
				'isMedia' => isset($value->isMedia)?$value->isMedia:false,
				'type' => $value->type,
				'isFavourite' => $value->isFavourite,
				'owner' => $this->getOwner(),
				'isEdited' => false,
				'createdAt' => Carbon::parse(Carbon::now())->toDateTimeString()
		];
		return $this->data;
	}

	public function designForNewCollab($value)
	{
		$this->data = [
			'title' => $value->title,
			'description' => $value->description,
			'isPublic' => $value->isPublic,
			'owner' => $this->getOwner(),
			'createdAt' => Carbon::parse(Carbon::now())->toDateTimeString()
		];
		return $this->data;
	}

	public function getOwner(){
		$owner = $this->user('id', \Auth::user()->id);
		return [
			'id' => $owner->id,
			'displayName' => $owner->display_name,
			'email' => $owner->email
		];
	}

	public function designForMembers($data)
	{
		$owner = $this->user('id', \Auth::user()->id);
		$data->details = [];
		$data->user = [];
		foreach ($data->members as $key => $value) {
			$struct = [
				'collabId' => $data->id,
				'title' => $data->title,
				'seen' => 0,
				'favourites' => [],
				'isOwner' => ($value == $owner->id)? true:false,
				'isManager' => ($value == $owner->id)?true:false,
				'isPublic' => $data->isPublic,
				'createdAt' => Carbon::parse(Carbon::now())->toDateTimeString()
			];
			array_push($data->details, $struct);
			$user = $this->user('id', $value);
			$userDetails = [
				'id' => $user->id,
				'displayName' => $user->display_name,
				'email' => $user->email,
				'isOwner' => $struct['isOwner'],
				'isManager' => ($struct['isOwner'] == true)?true:false
			];
			array_push($data->user, $userDetails);
		}
		return $data;
	}

	public function designForNewCard($value)
	{
		if($value->type == 'url')
			$url = (new UrlExtractor)->extract($value->data);
		$this->data = [
			'id' => $this->generateUUID(),
			'data' => $value->data,
			'urlMeta' => isset($url)?$url:NULL,
			'isMedia' => isset($value->isMedia)?$value->isMedia:false,
			'type' => $value->type,
			'members' => $value->members,
			'owner' => $this->getOwner(),
			'isEdited' => false,
			'createdAt' => Carbon::parse(Carbon::now())->toDateTimeString()
		];
		return $this->data;
	}

	public function frameComment($data)
	{
		$this->data = [
				'id' => $this->generateUUID(),
				'data' => $data->data,
				'members' => $data->members,
				'owner' => $this->getOwner(),
				'isEdited' => false,
				'createdAt' => Carbon::parse(Carbon::now())->toDateTimeString()
		];
		return $this->data;
	}

	public function frameReply($data)
	{
		$this->data = [
				'id' => $this->generateUUID(),
				'data' => $data->data,
				'members' => $data->members,
				'owner' => $this->getOwner(),
				'isEdited' => false,
				'createdAt' => Carbon::parse(Carbon::now())->toDateTimeString()
		];
		return $this->data;
	}
}