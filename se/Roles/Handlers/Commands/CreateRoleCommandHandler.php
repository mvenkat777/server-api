<?php

namespace Platform\Roles\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Roles\Repositories\Contracts\RoleRepository;


class CreateRoleCommandHandler implements CommandHandler
{
	protected $roleRepo;

	function __construct(RoleRepository $roleRepo)
	{
		$this->roleRepo = $roleRepo;
	}

	public function handle($command)
	{
		$data = ['name'=>$command->name,
				'description'=>$command->description,
				'group_id'=>$command->groupId,
				'apps_permissions'=> json_encode($command->appPermission)
				];
		$role=$this->roleRepo->createRole($data);
		//dd($role);
		return $role;
	}
} 