<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;
use Platform\Tasks\Events\TaskWasSubmitted;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;

class SubmitTaskCommandHandler implements CommandHandler
{  
	use DispatchesJobs;
	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepository
	 */
	protected $taskRepository;

	/**
     * @var DefaultRuleBus
     */
    protected $defaultRuleBus;

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
	public function __construct(
								TaskRepository $taskRepository,
								Guard $auth,
								TaskValidator $taskValidator, 
								DefaultRuleBus $defaultRuleBus)
	{
		$this->taskRepository = $taskRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
		$this->defaultRuleBus = $defaultRuleBus;
	}

	/**
	 * @param  SubmitTaskCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		if(!$this->taskValidator->isTaskSeen($command->id)){
            $command->seeTask = true;
			//throw new SeException("Please see task to proceed", 422, 786113);
		}

        /*
		if(!$this->taskValidator->isTaskStarted($command->id)){
			throw new SeException("Please start task to proceed", 422, 786114);
		}
         */

		if($this->taskValidator->isTaskCompleted($command->id) 
			|| $this->taskValidator->isTaskExpired($command->id)){
			throw new SeException("Task has expired/completed", 422, 786101);
		}

		if(!$this->taskValidator->isTaskAssignee($command->id)){
			throw new SeException("you are not assignee", 401, 786103);
		}
		$task = $this->taskRepository->submitTask($command);
		
		$task = $this->taskRepository->getTaskById($command->id);
  		$job = (new DefaultRuleBusJob($task, \Auth::user(), 'SubmitTask'));
         $this->dispatch($job);
		
		return $task;
	}

}
