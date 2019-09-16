<?php

namespace Platform\Tasks\Handlers\Commands;

use Illuminate\Auth\Guard;
use Platform\App\Commanding\CommandHandler;
use Platform\Tasks\Repositories\Contracts\TaskRepository;

class GetTaskByTypeCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @var Illuminate\Auth\Guard
	 */
	protected $auth;

	/**
	 * @param TaskRepository         $taskRepository       
	 */
	public function __construct(
		TaskRepository $taskRepository,
        Guard $auth
	){
		$this->taskRepository = $taskRepository;
		$this->auth = $auth;
	}

	/**
	 * @param  GetTaskByTypeCommand $command 
	 * @return mixed         
	 */
	public function handle($command)
	{
		return $this->taskRepository->getTaskByType($command->type, $this->auth->user(), $command->item);
	}

}
