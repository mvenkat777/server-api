<?php

namespace Platform\Roles\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Roles\Repositories\Contracts\RoleRepository;

class ShowRoleByIdCommandHandler implements CommandHandler
{
	protected $roleRepo;

	function __construct(RoleRepository $roleRepo)
	{
		$this->roleRepo = $roleRepo;
	}

	public function handle($command)
	{
		return $this->roleRepo->getByIdRole($command->id);
	}
}