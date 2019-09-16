<?php
namespace Platform\Collab\Handlers\Commands;
use Platform\Collab\Repositories\Repository;
use Platform\Collab\Repositories\DirectMessageRepository;
use Platform\Collab\Repositories\DirectMessagePermissionRepository;
use Carbon\Carbon;
/**
* To update the last seen of the user
* @return 
*/
class UpdateUserLastSeenStateCommandHandler extends Repository
{
	protected $directMessageRepository;
	function __construct(DirectMessageRepository $directMessageRepository, DirectMessagePermissionRepository $directMessagePermissionRepository)
	{
		$this->directMessageRepository = $directMessageRepository;
		$this->directMessagePermissionRepository = $directMessagePermissionRepository;
	}
	public function handle($command)
	{
		$unseenCount = 0;
		$now = Carbon::now()->toDateTimeString();
		$collection = $this->directMessagePermissionRepository->getGroupConversationIdByUserId($command->actor);
		foreach ($collection['group'] as $key => $value) {
			if(isset($value['chatId'])){
				$unseen = $this->directMessageRepository->getUnseenData($value['chatId'], $now);
				if($unseen)
				{
					$unseenCount = $unseenCount + $unseen;
				}
			}
		}

		foreach ($collection['chat'] as $key => $value) {
			if(isset($value['chatId'])){
				$unseen = $this->directMessageRepository->getUnseenData($value['chatId'], $now);
				if($unseen)
				{
					$unseenCount = $unseenCount + $unseen;
				}
			}
		}
		
		return $unseenCount;
	}
}