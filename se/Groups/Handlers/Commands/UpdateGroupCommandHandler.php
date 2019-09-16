<?php

namespace Platform\Groups\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Groups\Repositories\Contracts\GroupRepository;

class UpdateGroupCommandHandler implements CommandHandler
{
	protected $groupRepo;

	function __construct(GroupRepository $groupRepo)
	{
		$this->groupRepo = $groupRepo;
	}

	public function handle($command)
	{
		$this->groupRepo->UpdateGroup((array)$command);

		return 'Updated Successfully';
	}
}