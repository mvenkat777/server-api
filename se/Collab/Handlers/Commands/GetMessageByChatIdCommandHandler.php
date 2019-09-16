<?php
namespace Platform\Collab\Handlers\Commands;
use Platform\App\Commanding\CommandHandler;
use Platform\Collab\Repositories\DirectMessageRepository;
use Platform\Collab\Repositories\DirectMessagePermissionRepository;
use Platform\Collab\Repositories\Repository;

/**
* 
*/
class GetMessageByChatIdCommandHandler extends Repository implements CommandHandler
{
	
	function __construct(DirectMessageRepository $messageRepository, DirectMessagePermissionRepository $messagePermissionRepository)
	{
		$this->messageRepository = $messageRepository;
		$this->messagePermissionRepository = $messagePermissionRepository;
	}

	public function handle($command)
	{
		$history = $this->messageRepository->getConversationHistory($command->chatId);
		$collection = $this->messagePermissionRepository->getGroupConversationIdByUserId(\Auth::user()->id);
		$keys = array_keys($collection['group']);
		$chatKeys = array_keys($collection['chat']);
		if(array_search($command->chatId, $keys) !== false){
			$favourites = $collection['group'][$keys[array_search($command->chatId, $keys)]]['favourites'];
			$participant = $collection['group'][$keys[array_search($command->chatId, $keys)]]['participant'];
		} else{
			$favourites = [];
			foreach ($collection['chat'] as $key => $value) {
				if($value['chatId'] == $command->chatId){
					$favourites = $value['favourites'];
				}
			}
			// $favourites = $collection['chat'][$chatKeys[array_search($command->chatId, $chatKeys)]]['favourites'];
			// $participant = $collection['chat'][$chatKeys[array_search($command->chatId, $chatKeys)]]['participant'];
		}
		if(isset($participant)){
			foreach ($participant as $value) {
				$user[] = $this->userFramedData('id', $value);
			}
			$history['user'] = $user;
		}
		$data = $history['messages'];
		foreach ($data as $key => $value) {
			if(in_array($value['id'], $favourites))
			{
				$data[$key]['isFavourite'] = true;
			}
		}
		$history['messages'] = $data;
		return $history;
	}
}