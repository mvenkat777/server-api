<?php
namespace Platform\DirectMessage\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\DirectMessage\Repositories\PermissionRepository;

/**
* UpdateToSeenCommand $command
* @return mixed
*/
class UpdateToSeenCommandHandler implements CommandHandler
{
    /**
     * @var $permissionRepository
     */
    public $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        return $this->permissionRepository->seen($command->userId, $command->chatId, $command->messageId);
    }
}