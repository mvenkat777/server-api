<?php
namespace Platform\DirectMessage\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\DirectMessage\Repositories\MessageRepository;
use Platform\DirectMessage\Repositories\PermissionRepository;

/**
* GetDirectMessageCommandHandler $command
* @param $command
*/
class GetDirectMessageCommandHandler implements CommandHandler
{
	/**
	 * @var $messageRepository
	 */
	public $messageRepository;

	/**
	 * @var $permissionRepository
	 */
	public $permissionRepository;
	
	public function __construct(MessageRepository $messageRepository,
								PermissionRepository $permissionRepository)
	{
		$this->messageRepository = $messageRepository;
		$this->permissionRepository = $permissionRepository;
	}

	/**
	 * @param $command
	 * @return mixed
	 */
	public function handle($command)
	{
		if($command->id){
			$permissionHistory = $this->permissionRepository->getChatHistoryByUserIdAndChatId($command->userId, $command->id);
			$history = $this->messageRepository->getChatHistoryByChatId($command->id);
			return $this->getTransformedChatHistory($history->toArray(), $permissionHistory->toArray());
		} else {
			$permissionHistory = $this->permissionRepository->getChatHistoryByUserId($command->userId);
			return $this->getLastChat($permissionHistory->toArray());
		}
	}

	public function getTransformedChatHistory($data, $history)
	{
		$favouriteList = $history[0]['favourites'];
		$seenList = isset($history[0]['seenList']) ? $history[0]['seenList'] : [];
		foreach ($data['messages'] as $key => $value) {
			if(in_array($value['messageId'], $favouriteList) !== false)
			{
				$data['messages'][$key]['isFavourite'] = true;
			} else {
				$data['messages'][$key]['isFavourite'] = false;
			}
			if(in_array($value['messageId'], $seenList) !== false)
			{
				$data['messages'][$key]['isRead'] = true;
			} else {
				$data['messages'][$key]['isRead'] = false;
			}
		}
		return [
			'chatId' => $data['chatId'],
			'messages' => $data['messages']
		];
	}

	public function getLastChat($data)
	{
		$collection = [];
		if(count($data))
		{
			$value = [];
			foreach ($data as $key => $value) 
			{
				$lastMessage = $this->getLastMessage($value['chatId']);
				if(isset($value['seenList']) && in_array($lastMessage['messageId'], $value['seenList']) !== false){
					$isRead = true;
				} else {
					$isRead = false;
				}
				$content = [
					'members' => $value['members'],
					'isGroup' => $value['isGroup'],
					'chatId' => $value['chatId'],
					'isRead' => $isRead,
					// 'isEdited' => $value['isEdited'],
					'createdAt' => $value['createdAt'],
					'lastMessage' => $this->getLastMessage($value['chatId'])
				];
				array_push($collection, $content);	
			}
		}
		return $collection;
	}

	public function getLastMessage($chatId)
	{
		$data = [];
		$history = $this->messageRepository->getChatHistoryByChatId($chatId);
		$data = $history->toArray();
		if(count($data['messages']))
			return end($data['messages']);
		else 
			return NULL;
	}
}