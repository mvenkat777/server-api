<?php

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Tasks\Repositories\Contracts\TaskFollowerRepository;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;
use Platform\Tasks\Helpers\TaskHelper;
use Platform\App\Exceptions\SeException;
use App\User;

class AddTaskFollowerCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskFollowerRepository
	 */
	protected $taskFollowerRepository;

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
	 * @var Platform\Tasks\Helpers\TaskHelper
	 */
	protected $taskHelper;

	/**
     * @var DefaultRuleBus
     */
    protected $defaultRuleBus;
	
	/**
	 * @param TaskFollowerRepository $taskFollowerRepository
	 * @param TaskRepository         $taskRepository        
	 * @param TaskValidator          $taskValidator         
	 * @param Auth                   $auth                  
	 */
	public function __construct(TaskFollowerRepository $taskFollowerRepository,
								TaskRepository $taskRepository,
								TaskValidator $taskValidator,
								Guard $auth,
								TaskHelper $taskHelper)
	{
		$this->taskFollowerRepository = $taskFollowerRepository;
		$this->taskRepository = $taskRepository;
		$this->taskValidator = $taskValidator;
		$this->auth = $auth;
		$this->taskHelper = $taskHelper;
	}

	public function handle($command)
	{
		if($this->taskValidator->isTaskOwner($command->taskId)
			 || $this->taskValidator->isTaskAssignee($command->taskId)
			 || $this->taskValidator->isTaskFollower($command->taskId)){
			
			\DB::beginTransaction();
			foreach ($command->followers as $key => $follower) {
				$command->followers[$key] = $this->taskHelper->getAssigneeDetails($follower);
				if(!($this->taskFollowerRepository->isFollowerPresent($command->taskId, $command->followers[$key]))){
					$follower = $this->taskFollowerRepository->add([
						'taskId' => $command->taskId,
						'follower' => $command->followers[$key]
					]);
                    $getPriorityId = $this->taskRepository->getTaskById($command->taskId);
					$task = $this->taskRepository->getTaskById($command->taskId);
                    $priority = \App\Priority::where('id', $getPriorityId->priority_id)->first();
					$details = (new \Platform\Tasks\Transformers\TaskTransformer)->transform($task);
					$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'http://platform.sourceeasy.com';
					$details['taskLink'] = $origin.'/#/tasks/'.$follower->task_id;
					$details['otherDetails'] = $this->getOtherInfo($details);
					$details['userName'] = $follower->user->display_name;
                    $details['emailSubject'] = 'You are now following a '.$priority->priority.' priority task - '.$getPriorityId->title;
					
                     
				}
			}
			\DB::commit();
			return $this->taskRepository->getTaskById($command->taskId);
		}

		throw new SeException('You cannot add follower to this task.', 401, 786105);
	}

	private function getOtherInfo($task)
	{
		$data = "";
		if($task['customer'] !== null) {
	        $data = $data.$task['customer']['name'];
	    }
	    if($task['tna'] !== null) {
	        $data = $data. '<a href="'.$_SERVER['HTTP_ORIGIN'].'/#/tNa/edit/'.$task['tna']['tnaId'].'">List</a> ';
	    }
	    if($task['style'] !== null) {
	    	$data = $data. ' - <a href="'.$_SERVER['HTTP_ORIGIN'].'/#/techpack/edit/'.$task['style']['techpack']['id'].'/home"> Style </a>';
	    }
	    return $data;
	}
}
