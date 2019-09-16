<?php
namespace Platform\DirectMessage\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\DirectMessage\Repositories\MessageRepository;
use Platform\DirectMessage\Repositories\PermissionRepository;
use Platform\DirectMessage\Repositories\DirectMessageRepository;
use Platform\DirectMessage\Helpers\UrlMetaExtractor;
use Platform\DirectMessage\Helpers\PlatformExtractor;
use Carbon\Carbon;

/**
* StoreDirectMessageCommandHandler $command
*/
class StoreDirectMessageCommandHandler extends DirectMessageRepository implements CommandHandler
{
	/**
	 * @var $messageRepository
	 */
	public $messageRepository;

	/**
	 * @var $extractUrl
	 */
	public $extractUrl;

	/**
	 * @var PlatformExtractor
	 */
	public $extractPlatform;

	/**
	 * @var $permissionRepository
	 */
	public $permissionRepository;

	public function __construct(MessageRepository $messageRepository,  
								PermissionRepository $permissionRepository,
                                UrlMetaExtractor $extractUrl,
                                PlatformExtractor $extractPlatform)
	{
		$this->messageRepository = $messageRepository;
		$this->permissionRepository = $permissionRepository;
		$this->extractUrl = $extractUrl;
        $this->extractPlatform = $extractPlatform;
	}

	/**
	 * @param $command
	 * @return mixed
	 */
	public function handle($command)
	{
		$command->owner = $this->getAuth();
		$framedData = $this->frameMessage($command);
		$isSuccess = $this->messageRepository->storeMessage($framedData);
		if($isSuccess)
		{
			return $framedData;
		}
	}

	public function frameMessage($command)
	{
		$data = [
			'chatId' => $command->chatId,
			'message' => [
				'messageId' => $this->generateUUID(),
				'message' => $command->message,
				'url' => $this->getUrlMeta($command),
				'type' => $command->type,
				// 'isEdited' => false,
				'isFavourite' => $command->isFavourite,
				'members' => $command->members,
				'owner' => $command->owner,
				'createdAt' => Carbon::now()->toDateTimeString()
			]
		];
		return $data;
	}

	public function getUrlMeta($data)
	{
		if($data->type == 'url')
		{
			return $this->extractUrl->extract($data->message);
		}
		if($data->type == 'platform')
		{
			return $this->extractPlatform->extract($data->message);
		}
		return [];
	}
}
