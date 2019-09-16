<?php
namespace Platform\DirectMessage\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\DirectMessage\Repositories\PermissionRepository;

/**
* GetSharedDataCommandHandler $command
* @return mixed
*/
class GetSharedDataCommandHandler implements CommandHandler
{
	/**
	 * @var $permissionRepository
	 */
	public $permissionRepository;
	
	public function __construct(PermissionRepository $permissionRepository)
	{
		$this->permissionRepository = $permissionRepository;
	}

	public function handle($command)
	{
		$sharedFiles = $this->permissionRepository->getSharedFiles($command->userId, $command->chatId);
		if(count($sharedFiles->shared))
		{
			$this->getFormatedData($sharedFiles->shared);
		}
	}

	public function getFormatedData($data)
	{
		dd("shared Handler");
	}
}