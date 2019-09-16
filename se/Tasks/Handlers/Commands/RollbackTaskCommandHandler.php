<?php

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Tasks\Repositories\Contracts\TaskRepository;

class RollbackTaskCommandHandler implements CommandHandler 
{

	public function __construct(TaskRepository $tasksRepository)
	{
        $this->tasksRepository = $tasksRepository;
	}

	public function handle($command)
	{
        return $this->tasksRepository->rollback($command->taskId);
	}

}
