<?php

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Tasks\Repositories\Contracts\TaskFollowerRepository;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;
use Platform\App\Exceptions\SeException;

class DeleteTaskFollowerCommandHandler implements CommandHandler 
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
	 * @param TaskFollowerRepository $taskFollowerRepository
	 * @param TaskRepository        $taskRepository       
	 * @param Guard                 $auth                 
	 * @param TaskValidator         $taskValidator        
	 */
	public function __construct(TaskFollowerRepository $taskFollowerRepository, TaskRepository $taskRepository, Guard $auth, TaskValidator $taskValidator)
	{
		$this->taskFollowerRepository = $taskFollowerRepository;
		$this->taskRepository = $taskRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
	}

	public function handle($command)
	{
		if($this->taskValidator->isTaskOwner($command->taskId)){
			$result = $this->taskFollowerRepository->deleteFollower($command->followerId);
			if($result)
				return $this->taskRepository->getTaskById($command->taskId);
			else
				throw new SeException('You may have entered wrong follower id', 422, 786112);
		}
	}

}