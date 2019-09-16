<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Repositories\Contracts\AttachmentRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;

class UpdateAttachmentCommandHandler implements CommandHandler
{
	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepository
	 */
	protected $taskRepository;

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
	 * @param TaskRepository       $taskRepository      
	 * @param AttachmentRepository $attachmentRepository
	 * @param Guard                $auth                
	 * @param TaskValidator        $taskValidator       
	 */
	public function __construct(TaskRepository $taskRepository, AttachmentRepository $attachmentRepository, Guard $auth, TaskValidator $taskValidator)
	{
		$this->taskRepository = $taskRepository;
		$this->attachmentRepository = $attachmentRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
	}

	/**
	 * @param  UpdateAttachmentCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		// if($this->taskValidator->isTaskOwner($command->taskId)
		// 	 || $this->taskValidator->isTaskAssignee($command->taskId)
		// 	 || $this->taskValidator->isTaskFollower($command->taskId)){

			return $this->attachmentRepository->updateAttachment($command);
		// }
		// else{
		// 	throw new SeException("This task doesnot belong to you", 401, 786105);
		// }
	}

}
