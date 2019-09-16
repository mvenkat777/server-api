<?php

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Tasks\Repositories\Contracts\TaskRepository;

class ArchiveTaskCommandHandler implements CommandHandler 
{
    /**
     * @var Platform\Tasks\Repositories\Contracts\TaskRepository
     */
    protected $taskRepository;

    /**
     * @param   Platform\Tasks\Repositories\Contracts\TaskRepository    $taskRepository
     */
	public function __construct(TaskRepository $taskRepository)
	{
        $this->taskRepository = $taskRepository;
	}

	public function handle($command)
	{
        return $this->taskRepository->archive($command->id);
	}

}
