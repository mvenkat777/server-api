<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;

class ChangeTaskPriorityCommandHandler implements CommandHandler
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
	 * @var Platform\Tasks\Validators\TaskValidator
	 */
	protected $taskValidator;
	
	/**
	 * @param TaskRepository $taskRepository
	 * @param Guard          $auth          
	 * @param TaskValidator  $taskValidator 
	 */
	public function __construct(TaskRepository $taskRepository, Guard $auth, TaskValidator $taskValidator)
	{
		$this->taskRepository = $taskRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
	}

	/**
	 * @param  ChangeTaskPriorityCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		if($this->taskValidator->isTaskCompleted($command->taskId) || $this->taskValidator->isTaskExpired($command->taskId)){
			throw new SeException("Task is over", 422, 786101);
		}

		if($this->taskValidator->isTaskOwner($command->taskId) || $this->taskValidator->isTaskAssignee($command->taskId)){
			return $this->taskRepository->changePriority($command);
		}
		throw new SeException("This task doesnot belong to you", 401, 786105);
	}


}
