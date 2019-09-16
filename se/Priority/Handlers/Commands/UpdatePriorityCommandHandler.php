<?php

namespace Platform\Priority\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Priority\Repositories\Contracts\PriorityRepository;

class UpdatePriorityCommandHandler implements CommandHandler
{
	protected $PriorityRepo;

	function __construct(PriorityRepository $PriorityRepo)
	{
		$this->PriorityRepo = $PriorityRepo;
	}

	public function handle($command)
	{
		$result=$this->PriorityRepo->UpdatePriority((array)$command);
		
		return 'Updated Successfully';
	}
}