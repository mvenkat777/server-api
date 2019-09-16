<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;

class SeeTaskCommandHandler implements CommandHandler
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
	 * @param  SeeTaskCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		if($this->taskValidator->isTaskCompleted($command->taskId) || $this->taskValidator->isTaskExpired($command->taskId) || $this->taskValidator->isTaskSubmitted($command->taskId)){
			throw new SeException("Task has submitted/expired/completed", 422, 786101);
		}

		if($this->taskValidator->isTaskAssignee($command->taskId)){
			if($this->taskValidator->isTaskSeen($command->taskId)){
				throw new SeException("You have already seen this task", 422, 7876102);
			}
			return $this->taskRepository->seeTask($command);
		}
		throw new SeException("This task does not belong to you", 401, 786105);
	}
}
