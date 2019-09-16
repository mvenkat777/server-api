<?php

namespace Platform\Tasks\Handlers\Commands;

use Carbon\Carbon;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Helpers\TaskHelper;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Validators\TaskValidator;
use Platform\Tasks\Repositories\Contracts\TaskFollowerRepository;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;


class ReassignTaskCommandHandler implements CommandHandler 
{   
	use DispatchesJobs;
	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepositor
	 */
	protected $taskRepository;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskFollowerRepository
	 */
	protected $taskFollowerRepository;

	/**
	 * @var Platform\Tasks\Validators\TaskValidator
	 */
	protected $taskValidator;

	/**
	 * @var Platform\Tasks\Helpers\TaskHelper
	 */
	protected $taskHelper;

	/**
     * @var DefaultRuleBus
     */
    protected $defaultRuleBus;

	public function __construct(
		TaskRepository $taskRepository,
		TaskFollowerRepository $taskFollowerRepository,
		TaskValidator $taskValidator,
		TaskHelper $taskHelper,
		DefaultRuleBus $defaultRuleBus
	){
		$this->taskRepository = $taskRepository;
		$this->taskFollowerRepository = $taskFollowerRepository;
		$this->taskValidator = $taskValidator;
		$this->taskHelper = $taskHelper;
		$this->defaultRuleBus = $defaultRuleBus;
	}

	public function handle($command)
	{
		// $today = Carbon::now()->toDateTimeString();
		// if($command->dueDate < $today){
		// 	throw new SeException('Given Due date is less than today', 422, 786108);
		// }

        if($this->taskValidator->isTaskCompleted($command->taskId)) {
            throw new SeException("Task is already completed", 422, 786125);
        }

		try{
			\DB::beginTransaction();
			$task = $this->taskRepository->getTaskById($command->taskId);

            if($task->assignee->email === $command->assignee) {
			    throw new SeException("Cannot reassign task to assignee", 422, 786125);
            }

			$this->addAssigneeToFollower($task);
			
			if(is_null($command->dueDate)) {
				$command->dueDate = $task->due_date;
			}

			$command->assignee = $this->taskHelper->getAssigneeDetails($command->assignee);
			$task = $this->taskRepository->reassignTask($command);
			\DB::commit();
		} catch(Exception $e) {
			throw new SeException("Task reassigning failed", 422, 786125);
		}
			$job = (new DefaultRuleBusJob($task, \Auth::user(), 'ReassignTask'));
            $this->dispatch($job);
            
		return $task;
	}

	/**
	 * Add task assignee to follower if assignee is not a follower
	 * @param Task Model $task 
	 */
	private function addAssigneeToFollower($task)
	{
		$isFollower = $this->taskFollowerRepository
			->isFollowerPresent(
				$task->id,
				$task->assignee_id
		);
		if (!$isFollower) {
			$this->taskFollowerRepository->add([
				'taskId' => $task->id,
				'follower' => $task->assignee_id
			]);
		}
	}

}
