<?php

namespace Platform\Priority\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Users\Repositories\Contracts\UserTokenRepository;
use Platform\Priority\Repositories\Contracts\PriorityRepository;


class CreatePriorityCommandHandler implements CommandHandler
{
	protected $PriorityRepo;

	function __construct(PriorityRepository $PriorityRepo)
	{
		$this->PriorityRepo = $PriorityRepo;

	}

	public function handle($command)
	{	
		//dd($command);
		$cmd_data = ['priority' => $command->priority];
		$Priority=$this->PriorityRepo->createPriority($cmd_data);

		return $Priority;
	}
} 