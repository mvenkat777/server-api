<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Repositories\Contracts\AttachmentRepository;
use Platform\Tasks\Validators\TaskValidator;

class AddAttachmentCommandHandler implements CommandHandler
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
	 * @var Platform\Tasks\Validators\TaskValidator
	 */
	protected $taskValidator;

	/**
     * @var DefaultNotificationBus
     */
    protected $defaultNotificationBus;
	
	/**
	 * @param TaskRepository       $taskRepository      
	 * @param AttachmentRepository $attachmentRepository
	 * @param TaskValidator        $taskValidator       
	 */
	public function __construct(TaskRepository $taskRepository, 
								AttachmentRepository $attachmentRepository, 
								TaskValidator $taskValidator)
	{
		$this->taskRepository = $taskRepository;
		$this->attachmentRepository = $attachmentRepository;
		$this->taskValidator = $taskValidator;
	}

	/**
	 * @param  AddAttachmentCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{		
		// if($this->taskValidator->isTaskOwner($command->taskId) 
		// 		|| $this->taskValidator->isTaskAssignee($command->taskId) 
		// 		|| $this->taskValidator->isTaskFollower($command->taskId)){
        if($this->attachmentRepository->addAttachment($command)){
				return $this->taskRepository->getTaskById($command->taskId);
			}
		// }
		// else{
		// 	throw new SeException("This task doesnot belong to you", 401, 786105);
		// }
	}
}
