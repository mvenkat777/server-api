<?php

namespace Platform\Roles\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Roles\Repositories\Contracts\RoleRepository;

class SearchRolesByGroupCommandHandler implements CommandHandler
{
	protected $roleRepo;

	function __construct(RoleRepository $roleRepo)
	{
		$this->roleRepo = $roleRepo;
	}

	public function handle($command)
	{
		return $this->roleRepo->getRolesByGroupId($command->id);
	}
}