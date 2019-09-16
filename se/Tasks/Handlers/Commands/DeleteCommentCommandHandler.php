<?php

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\TaskCommentRepository;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;

class DeleteCommentCommandHandler implements CommandHandler 
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
	 * @param TaskCommentRepository $taskCommentRepository
	 * @param TaskRepository        $taskRepository       
	 * @param Guard                 $auth                 
	 * @param TaskValidator         $taskValidator        
	 */
	public function __construct(TaskCommentRepository $taskCommentRepository, TaskRepository $taskRepository, Guard $auth, TaskValidator $taskValidator)
	{
		$this->taskCommentRepository = $taskCommentRepository;
		$this->taskRepository = $taskRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
	}

	/**
	 * @param  DeleteCommentCommand $command
	 * @return mixed        
	 */
	public function handle($command)
	{
		// if($this->taskValidator->isTaskOwner($command->taskId) || $this->taskValidator->isTaskAssignee($command->taskId)){
			
			$result = $this->taskCommentRepository->deleteComment($command->commentId);
			if($result)
				return $this->taskRepository->getTaskById($command->taskId);
			else
				throw new SeException('Comment id or task id may be wrong', 500, 50000);
		// }

		throw new SeException('You cannot alter comment in this task.', 401, 786105);
	}
}