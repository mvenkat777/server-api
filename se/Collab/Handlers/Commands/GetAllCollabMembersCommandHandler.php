<?php
namespace Platform\Collab\Handlers\Commands;

use Platform\Collab\Repositories\PermissionRepository;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Collab\Repositories\Repository;

/**
* GetAllCollabMembersCommand $command
* @return mixed 
*/
class GetAllCollabMembersCommandHandler extends Repository implements CommandHandler
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
     * @param  GetAllCollabMembersCommand $command 
     * @return mixed          
     */
	public function handle($command)
	{
		$list = $this->members->getUserIdByCollab($command->collabId);
		$members = $list->members;
		$data = [];
		foreach ($members as $key => $value) {
			if(count($value)){
				array_push($data, $value);
			}
		}
		return $data;
	}
}