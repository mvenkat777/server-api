<?php

namespace Platform\Apps\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Apps\Repositories\Contracts\AppRepository;

class DeleteAppCommandHandler implements CommandHandler
{
	protected $appRepo;

	function __construct(AppRepository $appRepo)
	{
		$this->appRepo = $appRepo;
	}

	public function handle($command)
	{
		$result = $this->appRepo->deleteApp($command->id);
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