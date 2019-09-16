<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\AttachmentRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;

class DeleteAttachmentCommandHandler implements CommandHandler
{
	/**
	 * @var Platform\Tasks\Repositories\Contracts\AttachmentRepositor
	 */
	protected $attachmentRepository;

	/**
	 * @var Illuminate\Auth\Guar
	 */
	protected $auth;

	/**
	 * @var Platform\Tasks\Validators\TaskValidator
	 */
	protected $taskValidator;
	
	/**
	 * @param AttachmentRepository $attachmentRepository
	 * @param Guard                $auth                
	 * @param TaskValidator        $taskValidator       
	 */
	public function __construct(AttachmentRepository $attachmentRepository, Guard $auth, TaskValidator $taskValidator)
	{
		$this->attachmentRepository = $attachmentRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
	}

	/**
	 * @param  DeleteAttachmentCommand $command
	 * @return Number of rows deleted
	 */
	public function handle($command)
	{
		// if($this->taskValidator->isTaskOwner($command->taskId) 
		// 	|| $this->taskValidator->isTaskAssignee($command->taskId) 
		// 	|| $this->taskValidator->isTaskFollower($command->taskId)){

			$result = $this->attachmentRepository->deleteAttachment($command->attachmentId);
			if($result == 0)
				throw new SeException("Attachment does not exist", 422, 786100);
			else
				return $result;
		// }
		// else{
		// 	throw new SeException("This task does not belong to you", 401, 786105);
		// }
	}


}
