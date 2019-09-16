<?php

namespace Platform\Priority\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Priority\Repositories\Contracts\PriorityRepository;

class SearchAllPriorityCommandHandler implements CommandHandler
{
	protected $PriorityRepo;

	function __construct(PriorityRepository $PriorityRepo)
	{
		$this->PriorityRepo = $PriorityRepo;
	}

	public function handle($command)
	{
		return $this->PriorityRepo->allPriority();
	}
}