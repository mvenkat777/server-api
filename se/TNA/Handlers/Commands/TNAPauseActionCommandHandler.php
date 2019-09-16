<?php

namespace Platform\TNA\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\TNA\Repositories\Contracts\TNAItemRepository;

class TNAPauseActionCommandHandler implements CommandHandler 
{
	protected $tnaItemRepository;

	public function __construct(TNAItemRepository $tnaItemRepository)
	{
		$this->tnaItemRepository = $tnaItemRepository;
	}

	public function handle($command)
	{
		// dd($command->tna);
		//get currentyl dispatched task
		// $tasks = $this->tnaItemRepository->getDispatchedItems($command->tna->id);
		//cancel all the task which are not finished
		// $this->cancelAllTasks($tasks);
	}

	private function cancelAllTasks($tasks)
	{
		foreach ($tasks as $task) {
			// $this->taskRepository->
		}
		
	}

}