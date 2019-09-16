<?php

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Tasks\Repositories\Contracts\TaskRepository;

class GetArchivedTaskByTypeCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @param TaskRepository         $taskRepository       
	 */
	public function __construct(TaskRepository $taskRepository){
		$this->taskRepository = $taskRepository;
	}

	public function handle($command)
	{
        return $this->taskRepository->getArchivedTaskByType($command->type, \Auth::user(), $command->item);
	}

}
