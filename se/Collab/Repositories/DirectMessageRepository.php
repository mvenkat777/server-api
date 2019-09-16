<?php
namespace Platform\Collab\Repositories;

use Platform\Collab\Repositories\Repository;
use Platform\Collab\Models\OneToOneMessage;

/* To make all the database transactions for direct messages */
class DirectMessageRepository extends Repository{

	/**
	 * @var message
	 */
	protected $message;

	public function __construct(OneToOneMessage $message)
	{
		$this->message = $message;
	}

	/**
	 * To store new conversation between users
     * @param  StoreNewDirectMessageCommandHandler $data 
     * @return mixed       
     */
	public function store($data)
	{
		return $this->message->create($data);
	}

	/**
	 * To store new conversation between users
     * @param  StoreNewDirectMessageCommandHandler $data 
     * @return mixed       
     */
	public function update($chatId, $column, $data)
	{
		return $this->message->where('chatId', $chatId)->push([$column => $data]);
	}

	/**
	 * To store new conversation between users
     * @param  StoreNewDirectMessageCommandHandler $data 
     * @return mixed       
     */
	public function updateMessage($chatId, $messageId, $data)
	{
		return $this->message->where('chatId', $chatId)
							 ->where('messages.id', $messageId)
							 ->update([
							 	'messages.$.message' => $data['message'],
							 	'messages.$.isEdited' => $data['isEdited']
							 ]);
	}

	/**
	 * To get history of conversation between users
     * @param  conversation Id $conversationId 
     * @return mixed       
     */
	public function getConversationHistory($conversationId)
	{
		$collection = $this->message->where('chatId', $conversationId)->select('chatId','messages')->first();
		if(!$collection){
			$collection = [
				'chatId' => $conversationId,
				'count' => 0,
				'messages' => [
				]
			];
			$collection = json_decode(json_encode($collection));
		} else {
			$collection->messageCount = count($collection->messages);
		}
		return $collection;
	}

	public function getUnseenData($chatId, $time)
	{
		$count = 0;
		$collection = $this->getConversationHistory($chatId);
		if(isset($collection['messages'])){
			foreach ($collection['messages'] as $key => $value) {
				if(isset($value['createdAt']))
				{
					if($value['createdAt'] > $time){
						$count = $count + 1;
					}
				}
			}
		}
		return $count;
	}

	public function getGroupConversationHistory($conversationId)
	{
		$collection = $this->message->where('chatId', $conversationId)->select('chatId','messages')->first();
		if(!$collection){
			$collection = [
				'chatId' => $conversationId,
				'count' => 0,
				'messages' => [
				]
			];
			$collection = json_decode(json_encode($collection));
		} else {
			$collection->messageCount = count($collection->messages);
		}
		return $collection;
	}

	public function archive($chatId, $messageId)
	{
		$toArchive = $this->getMessageById($chatId, $messageId);
		if(isset($toArchive->messages) && $toArchive->messages){
			$isArchived = $this->message->where('chatId', $chatId)->pull(['messages' =>['id' => $messageId]]);
			
			if($isArchived){
				$data = $toArchive['messages'][0];
				$data['createdAt'] = $this->getCurrentDateTime();
				$data['ArchivedBy'] = $this->userFramedData('id',\Auth::user()->id);
				$this->update($chatId, 'archived', $data);
				return $this->getConversationHistory($chatId);
			}
		} else {
			return false;
		}
	}

	public function getMessageById($chatId, $messageId){
		return $this->message->where('chatId', $chatId)->
			project([
				'messages' => [
					'$elemMatch' => [
						"id" => $messageId
					]
				]
		])->first();
	}

	public function getPlatformMessagesByUserAndType($chatId, $userId, $type){
		// return $this->message->where('chatId', $chatId)->
		// 	project([
		// 		'messages' => [
		// 			'$elemMatch' => [
		// 				"owner.id" => $userId,
		// 				"type" => $type
		// 			]
		// 		]
		// 	])->get();
		// //dd($typeData->toArray());	

		$data = [];
		$collection = $this->message->where('chatId', $chatId)->first();
		if($collection){
			foreach ($collection->messages as $key => $value) {
				if($value['type'] == $type){
					if($value['owner']['id'] == $userId)
						array_push($data, $value);
				}
			}
		} else {
			return $collection;
		}	
		return $data;
	}

	public function getMediaMessagesByUserAndType($chatId, $userId)
	{
		$data = [];
		$collection = $this->message->where('chatId', $chatId)->first();
		if($collection){
			foreach ($collection->messages as $key => $value) {
				if(isset($value['isMedia']) && $value['isMedia'] == true){
					if($value['owner']['id'] == $userId)
						array_push($data, $value);
				}
			}
		} else {
			return $collection;
		}	
		return $data;
	}

	public function getMediaMessagesWithUserAndType($chatId, $userId)
	{
		$data = [];
		$collection = $this->message->where('chatId', $chatId)->first();
		if($collection){
			foreach ($collection->messages as $key => $value) {
				if((isset($value['isMedia']) && $value['isMedia'] == true) || $value['type'] == 'url' || $value['type'] == 'note'){
					if($value['owner']['id'] != $userId)
						array_push($data, $value);
				}
			}
		} else {
			return $collection;
		}	
		return $data;
	}

	public function getPlatformMessagesWithUserAndType($chatId, $userId, $type)
	{
		$data = [];
		$collection = $this->message->where('chatId', $chatId)->first();
		if($collection){
			foreach ($collection->messages as $key => $value) {
				if((isset($value['isMedia']) && $value['isMedia'] == true) || $value['type'] == $type){
					if($value['owner']['id'] != $userId)
						array_push($data, $value);
				}
			}
		} else {
			return $collection;
		}	
		return $data;
	}
}