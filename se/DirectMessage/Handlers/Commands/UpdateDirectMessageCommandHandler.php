<?php
namespace Platform\DirectMessage\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\DirectMessage\Repositories\MessageRepository;
use Platform\DirectMessage\Repositories\PermissionRepository;

/**
 * UpdateDirectMessageCommandHandler $command
 * @return mixed
 */
class UpdateDirectMessageCommandHandler implements CommandHandler
{
	/**
	 * @var $messageRepository
	 */
	public $messageRepository;

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
		if($command->key == 'favourite'){
			$setFavourite = $this->permissionRepository->setFavourite($command, \Auth::user()->id);
			if($setFavourite)
			{
				$data = $this->getMessageById($command);
				$data['isFavourite'] = true;
				return $data;
			}
		}
		if($command->key == 'message'){
			$auth = \Auth::user()->id;
			$updateMessage = $this->messageRepository->updateMessage((array)$command,  $auth);
			if($updateMessage)
			{
				$permissionHistory = $this->permissionRepository->getChatHistoryByUserId($auth)->toArray();
				$favouriteList = $permissionHistory[0]['favourites'];
				$data = $this->getMessageById($command);
				// return $data;
				if(in_array($data['messageId'], $favouriteList))
				{
					$data['isFavourite'] = true;
				}
				return $data;
			}
		}
		return [];
	}

	public function getMessageById($data)
	{
		return $this->messageRepository->getMessageByMessageId($data->chatId, $data->messageId);
	}
}