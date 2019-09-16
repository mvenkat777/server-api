<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;
use Platform\Tasks\Events\TaskWasClosed;
use Platform\Observers\Tasks\TaskObserver;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;


class CloseTaskCommandHandler implements CommandHandler
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
	 * @var Platform\Observers\Tasks\TaskObserver
	 */
	protected $taskObserver;
	
	/**
	 * @param TaskRepository $taskRepository
	 * @param Guard          $auth          
	 * @param TaskValidator  $taskValidator 
	 */
	public function __construct( 
								TaskRepository $taskRepository, 
								Guard $auth, 
								TaskValidator $taskValidator, 
								DefaultRuleBus $defaultRuleBus,
								TaskObserver $taskObserver)
	{
		$this->taskRepository = $taskRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
		$this->defaultRuleBus = $defaultRuleBus;
		$this->taskObserver = $taskObserver;
	}

	/**
	 * @param  CloseTaskCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		$task = $this->taskRepository->closeTask($command);

        /*
		$task->taskLink = $_SERVER['HTTP_ORIGIN'].'/#/tasks/list?q=assigned';
		$this->defaultRuleBus->setReceiver($task->assignee->email)
								->execute('TaskWasClosed', $task);
         */
		$task->note = $command->note;
		$this->taskObserver->taskReject($task);
		$getTask = $this->taskRepository->getTaskById($command->taskId);
		$getTask->note = $task->note;
		$job = (new DefaultRuleBusJob($getTask, \Auth::user(), 'CloseTask'));
         $this->dispatch($job);

		return $task;
	}


}
