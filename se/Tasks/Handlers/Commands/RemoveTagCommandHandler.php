<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Repositories\Contracts\TagRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;
use Platform\Tasks\Helpers\TaskHelper;

class RemoveTagCommandHandler implements CommandHandler
{
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
	
	public function __construct(TaskRepository $taskRepository,
								Guard $auth,
								TaskValidator $taskValidator,
								TagRepository $tagRepository,
								TaskHelper $taskHelper)
	{
		$this->taskRepository = $taskRepository;
		$this->tagRepository = $tagRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
		$this->taskHelper = $taskHelper;
	}

	/**
	 * @param  RemoveTagCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		// if($this->taskValidator->isTaskOwner($command->id) || $this->taskValidator->isTaskAssignee($command->id)){
			// $command->tag = $this->taskHelper->convertTag($command->tag);
			return $this->taskRepository->removeTag($command);
		// }
		// throw new SeException("This task does not belong to you", 401, 786105);
	}

}
