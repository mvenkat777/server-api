<?php

namespace Platform\Roles\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Roles\Repositories\Eloquent\EloquentRoleUserRepository;

class GetAllUserRolesCommandHandler implements CommandHandler
{
	protected $roleUserRepo;

	function __construct(EloquentRoleUserRepository $roleUserRepo)
	{ 
		$this->roleUserRepo = $roleUserRepo;
	}

	public function handle($command)
	{
		return $this->roleUserRepo->allUserRoles($command->id);
	}
}