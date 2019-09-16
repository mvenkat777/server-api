<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\Collab\Repositories\PermissionRepository;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\Repository;

/**
* AssignAsManagerCommand $command
* @return mixed
*/
class CollabManagerCommandHandler extends Repository implements CommandHandler
{
	
	/**
     * @var Platform\Collab\Repositories\PermissionRepository
     */
    private $members;

    public function __construct(PermissionRepository $members)
	{
		$this->members     = $members;
	}

	/**
     * @param  AssignAsManagerCommand $command 
     * @return mixed          
     */
	public function handle($command)
	{	if($command->set){
			$list = $this->members->assignManager($command->collabId, $command->userId);
		} else {
			$list = $this->members->removeManager($command->collabId, $command->userId);
		}
		if($list){
			return $this->members->getUserIdByCollab($command->collabId);
		} else {
			throw new SeException("Failed to perform action. Try Again", 422, '90010422');
		}
	}
}