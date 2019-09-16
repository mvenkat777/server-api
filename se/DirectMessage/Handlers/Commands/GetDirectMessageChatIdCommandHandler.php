<?php
namespace Platform\DirectMessage\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\DirectMessage\Repositories\PermissionRepository;
use Platform\DirectMessage\Repositories\MessageRepository;
use Platform\DirectMessage\Repositories\DirectMessageRepository;

/**
*  GetDirectMessageChatIdCommandHandler $command
*/
class GetDirectMessageChatIdCommandHandler extends DirectMessageRepository implements CommandHandler
{
	/**
	 * @var permissionRepository
	 */

	public function __construct(PermissionRepository $permissionRepository, 
								MessageRepository $messageRepository)
	{
		$this->permissionRepository = $permissionRepository;
		$this->messageRepository = $messageRepository;
	}

	public function handle($command)
	{
		$auth = \Auth::user()->id;
		array_push($command->members, $auth);
		$members = array_values(array_unique($command->members));
		$fetchChatId = $this->permissionRepository->getMatchedChatId($auth, $command->isGroup);
		if(count($fetchChatId))
		{
			return $this->getChatId($fetchChatId, $command);
		}
		else {
			$newDetails = $this->frameNewChatId($command);
			return $newDetails;
		}
	}

	public function getChatId($data, $command)
	{
		$collection = $data->toArray();
		foreach ($collection as $key => $value) {
			$existing = array_column($value['members'], 'id');
			$members = array_merge(array_diff($command->members, $existing), array_diff($existing, $command->members));
			if(!count($members))
			{
				return [
					'chatId' => $value['chatId'],
					'members' => $value['members'], 
					'messages' => []
				];
			}
		}
		$newDetails = $this->frameNewChatId($command);
		return $newDetails;
	}

	public function frameNewChatId($data)
	{
		$user = [];
		foreach ($data->members as $key => $value) {
			array_push($user, $this->getUser('id', $value));
		}
		$data->members = $user;
		$data->chatId = $this->generateUUID();
		$isAdded = $this->permissionRepository->storePermission($data);
		if($isAdded)
		{
			$message = [
				'chatId' => $data->chatId,
				'messages' => [],
				'archived' => [] 
			];
			$this->messageRepository->createMessageFrame($message);
			return [
					'chatId' => $data->chatId,
					'members' => $data->members, 
					'messages' => []
				];
		}
	}
}