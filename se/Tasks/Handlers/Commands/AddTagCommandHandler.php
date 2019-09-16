<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Repositories\Contracts\TagRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;
use Platform\Tasks\Helpers\TaskHelper;

use Platform\Tasks\Commands\CreateTagCommand;

class AddTagCommandHandler implements CommandHandler
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
	 * @param DefaultCommandBus $commandBus    
	 * @param TaskRepository    $taskRepository
	 * @param Guard             $auth          
	 * @param TaskValidator     $taskValidator 
	 * @param TagRepository     $tagRepository 
	 */
	public function __construct(TaskRepository $taskRepository,
								Guard $auth,
								TaskValidator $taskValidator,
								TaskHelper $taskHelper)
	{
		$this->taskRepository = $taskRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
		$this->taskHelper = $taskHelper;
	}

	/**
	 * @param  AddTagCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		// if($this->taskValidator->isTaskCompleted($command->taskId) || $this->taskValidator->isTaskExpired($command->taskId)){
		// 	throw new SeException("Task is over", 422, 786101);
		// }

		if($this->taskValidator->isTaskOwner($command->taskId) 
			|| $this->taskValidator->isTaskAssignee($command->taskId) 
			|| $this->taskValidator->isTaskFollower($command->taskId)){

			$command->tag = $this->taskHelper->changeToTagId([$command->tag]);
			return $this->taskRepository->addTag($command);
		}
		throw new SeException("This task does not belong to you", 401, 786105);
	}

}
