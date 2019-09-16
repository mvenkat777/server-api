<?php 
namespace Platform\Tasks\Handlers\Reminder;

use Platform\App\RuleCommanding\DefaultRuleBus;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;

class TaskReminderCommandHandler
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
	 * @param DefaultCommandBus $commandBus    
	 * @param TaskRepository    $taskRepository
	 */
	public function __construct(
								TaskRepository $taskRepository,
								DefaultRuleBus $defaultRuleBus
								)
	{
		$this->taskRepository = $taskRepository;
		$this->defaultRuleBus = $defaultRuleBus;
	}

	/**
	 * @param  UpdateTaskCommand $command
	 * @return mixed
	 */
	public function handle()
	{
		$collection = $this->taskRepository->getTaskRemindeForOverDueDate();
		// $this->defaultRuleBus->execute($collection, 'se-bot', 'PastDueDateReminder');
		$job = (new DefaultRuleBusJob($collection,'se-bot', 'PastDueDateReminder'));
         $this->dispatch($job);
		return $collection;
	}
}
