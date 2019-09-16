<?php

namespace Platform\Groups\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Groups\Repositories\Contracts\GroupRepository;

class DeleteGroupCommandHandler implements CommandHandler
{
	protected $groupRepo;

	function __construct(GroupRepository $groupRepo)
	{
		$this->groupRepo = $groupRepo;
	}

	public function handle($command)
	{
		$result=$this->groupRepo->deleteGroup($command->id);
		if($result == 1)
		{
			return 'Deleted Successfully';
		}
		else{
			return 'No record Found';
		}
	}
}