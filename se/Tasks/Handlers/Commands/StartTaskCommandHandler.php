<?php

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Validators\TaskValidator;
use Platform\App\RuleCommanding\DefaultRuleBus;

class StartTaskCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @var Platform\Tasks\Validators\TaskValidator
	 */
	protected $taskValidator;

	/**
     * @var DefaultRuleBus
     */
    protected $defaultRuleBus;

	public function __construct(TaskRepository $taskRepository,
								TaskValidator $taskValidator,
								DefaultRuleBus $defaultRuleBus)
	{
		$this->taskRepository = $taskRepository;
		$this->taskValidator = $taskValidator;
		$this->defaultRuleBus = $defaultRuleBus;
	}

	public function handle($command)
	{
		if(!$this->taskValidator->isTaskSeen($command->taskId)){
            $command->seeTask = true;
			//throw new SeException("Please see task to proceed", 422, 786113);
		}

		if($this->taskValidator->isTaskCompleted($command->taskId) 
			|| $this->taskValidator->isTaskExpired($command->taskId)){
			throw new SeException("Task has expired/completed", 422, 786101);
		}

		if(!$this->taskValidator->isTaskAssignee($command->taskId)){
			throw new SeException("you are not assignee", 401, 786103);
		}

		$task = $this->taskRepository->startTask($command->taskId, $command->seeTask);

        /*
		$task->taskLink = $_SERVER['HTTP_ORIGIN'].'/#/tasks/list?q=me';
		$this->defaultRuleBus->setReceiver($task->creator->email)
								->execute('TaskWasStarted', $task);
         */
		return $task;
	}

}
