<?php

namespace Platform\Roles\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Roles\Repositories\Contracts\RoleRepository;

class UpdateRoleCommandHandler implements CommandHandler
{
	protected $roleRepo;

	function __construct(RoleRepository $roleRepo)
	{
		$this->roleRepo = $roleRepo;
	}

	public function handle($command)
	{ //dd($command);
		$data = ['id'=> $command->id,
				'name'=>$command->name,
				'description'=>$command->description,
				'group_id'=>$command->groupId,
				'apps_permissions'=> json_encode($command->appPermission)
				];
		$result=$this->roleRepo->UpdateRole($data);
		
		return 'Updated Successfully';
	}
}