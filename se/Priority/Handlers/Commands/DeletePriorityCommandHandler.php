<?php

namespace Platform\Priority\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Priority\Repositories\Contracts\PriorityRepository;

class DeletePriorityCommandHandler implements CommandHandler
{
	protected $PriorityRepo;

	function __construct(PriorityRepository $PriorityRepo)
	{
		$this->PriorityRepo = $PriorityRepo;
	}

	public function handle($command)
	{
		$result = $this->PriorityRepo->deletePriority($command->id);
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