<?php 

namespace Platform\Tasks\Handlers\Commands;

use Illuminate\Auth\Guard;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\App\Exceptions\SeException;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Platform\TNA\Commands\SyncCommand;
use Platform\TNA\Handlers\Console\TNAProjectedDateCalculator;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Repositories\Contracts\TNAItemRepository;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\Tasks\Events\TaskWasCompleted;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Validators\TaskValidator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\Tasks\Jobs\SetTNAItemStatusJob;
use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;


class CompleteTaskCommandHandler implements CommandHandler
{
	use EventGenerator;
	use DispatchesJobs;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepository
	 */
	protected $taskRepository;
	
	/**
	 * @var Platform\TNA\Repositories\Contracts\TNAItemRepository
	 */
	protected $tnaItemRepository;

	/**
	 * @var Platform\TNA\Repositories\Contracts\TNARepository
	 */
	protected $tnaRepository;

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
	 * @var Platform\TNA\Helpers\TNAHelper
	 */
	protected $tnaHelper;

	/**
	 * @var Platform\App\Commanding\DefaultCommandBus
	 */
	protected $commandBus;
	
	/**
	 * @param TaskRepository $taskRepository
	 * @param Guard          $auth          
	 * @param TaskValidator  $taskValidator 
	 */
	public function __construct(TaskRepository $taskRepository, 
								TNAItemRepository $tnaItemRepository, 
								TNARepository $tnaRepository, 
								Guard $auth, 
								TaskValidator $taskValidator, 
								TNAHelper $tnaHelper, 
								DefaultRuleBus $defaultRuleBus,
								DefaultCommandBus $commandBus)
	{
		$this->taskRepository = $taskRepository;
		$this->tnaItemRepository = $tnaItemRepository;
		$this->tnaRepository = $tnaRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
		$this->tnaHelper = $tnaHelper;
		$this->defaultRuleBus = $defaultRuleBus;
		$this->commandBus = $commandBus;
	}

	/**
	 * @param  CompleteTaskCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		if($this->taskValidator->isTaskCompleted($command->id)){
			throw new SeException("Task is already Completed", 403, 786101);
		}

		// if(!$this->taskValidator->isTaskOwner($command->id)){
		// 	throw new SeException("you are not owner", 401, 786105);
		// }

		$task = $this->taskRepository->completeTask($command);

		if(!is_null($task->tna_item_id)){
			// dd($task->tna_item_id);
			// $this->setTNAItemStatus($task);
			$job = new SetTNAItemStatusJob($task);
			$this->dispatch($job);
		}
        $job = (new DefaultRuleBusJob($task, \Auth::user(), 'CompleteTask'));
            $this->dispatch($job);
		return $task;
	}

	private function isLastItem($tnaItemId, $itemsOrder)
	{
		return $tnaItemId == $itemsOrder[count($itemsOrder)-1]->itemId;
	}

}
