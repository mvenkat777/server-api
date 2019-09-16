<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Repositories\Contracts\TaskCommentRepository;
use Platform\Tasks\Repositories\Contracts\TaskFollowerRepository;
use Platform\Tasks\Repositories\Contracts\AttachmentRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;

class DeleteTaskCommandHandler implements CommandHandler
{
	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskCommentRepository
	 */
	protected $taskCommentRepository;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskFollowerRepository
	 */
	protected $taskFollowerRepository;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\AttachmentRepository
	 */
	protected $attachmentRepository;

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
		TaskCommentRepository $taskCommentRepository,
		TaskFollowerRepository $taskFollowerRepository,
		AttachmentRepository $attachmentRepository,
		Guard $auth,
		TaskValidator $taskValidator
	){
		$this->taskRepository = $taskRepository;
		$this->attachmentRepository = $attachmentRepository;
		$this->taskCommentRepository = $taskCommentRepository;
		$this->taskFollowerRepository = $taskFollowerRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
	}

	/**
	 * @param  DeleteTaskCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		if($this->taskValidator->isTaskOwner($command->id)){
			\DB::beginTransaction();
			$result = $this->taskRepository->deleteTask($command);
			$this->attachmentRepository->deleteForTasks($command->id);
			$this->taskCommentRepository->deleteForTasks($command->id);
			$this->taskFollowerRepository->deleteForTasks($command->id);
			\DB::commit();
			return $result;
		}
		else{
			throw new SeException("This Task is not for you", 401, 786105);
		}
	}

}
