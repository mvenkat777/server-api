<?php

namespace Platform\Tasks\Handlers\Commands;

use Illuminate\Auth\Guard;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Helpers\TaskHelper;
use Platform\Tasks\Repositories\Contracts\TaskCommentRepository;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Validators\TaskValidator;
use Platform\Users\Repositories\Contracts\UserRepository;

class AddCommentCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskCommentRepository
	 */
	protected $taskCommentRepository;

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
     * @var DefaultRuleBus
     */
    protected $defaultRuleBus;

    protected $userRepo;
	
	/**
	 * @param TaskCommentRepository $taskCommentRepository
	 * @param TaskRepository        $taskRepository       
	 * @param Guard                 $auth                 
	 * @param TaskValidator         $taskValidator 
	 * @param DefaultRuleBus    	$defaultRuleBus       
	 * @param UserRepository    	$userRepo       
	 */
	public function __construct(TaskCommentRepository $taskCommentRepository, 
								TaskRepository $taskRepository, 
								Guard $auth, 
								TaskValidator $taskValidator,
								UserRepository $userRepo)
	{
		$this->taskCommentRepository = $taskCommentRepository;
		$this->taskRepository = $taskRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
		$this->userRepo = $userRepo;
	}

	/**
	 * @param  AddCommentCommand $command
	 * @return mixed        
	 */
	public function handle($command)
	{
		// if($this->taskValidator->isTaskOwner($command->taskId)
		// 	 || $this->taskValidator->isTaskAssignee($command->taskId)
		// 	 || $this->taskValidator->isTaskFollower($command->taskId)){
			
			$command->owner = $this->auth->user()->id;
			
			$taskComment = $this->taskCommentRepository->add($command);
			if($taskComment){
				$task = $this->taskRepository->getTaskById($command->taskId);
				return $task;
			}
			else{
				throw new SeException('Some problems occured', 500, 50000);
			}
		// }
		// throw new SeException('You cannot comment in this task.', 401, 786105);
	}
}
