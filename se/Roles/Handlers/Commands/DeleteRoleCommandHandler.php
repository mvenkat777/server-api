<?php

namespace Platform\Roles\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Roles\Repositories\Contracts\RoleRepository;

class DeleteRoleCommandHandler implements CommandHandler
{
	protected $roleRepo;

	function __construct(RoleRepository $roleRepo)
	{
		$this->roleRepo = $roleRepo;
	}

	public function handle($command)
	{
		$result = $this->roleRepo->deleteRole($command->id);
		if($result == 1)
		{
			return 'Deleted Successfully';
		}
		else
		{
			return 'No Record Found';
		}
	}
}