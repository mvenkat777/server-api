<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Illuminate\Auth\Guard;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Validators\TaskValidator;

class GetTaskByIdCommandHandler implements CommandHandler
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
	 * @param TaskRepository $taskRepository
	 * @param Guard          $auth          
	 * @param TaskValidator  $taskValidator 
	 */
	public function __construct(
		TaskRepository $taskRepository, 
		Guard $auth, 
		TaskValidator $taskValidator
	){
		$this->taskRepository = $taskRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
	}

	/**
	 * @param  GetTaskByIdCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		if( $this->taskValidator->isTaskOwner($command->id) ||
			$this->taskValidator->isTaskAssignee($command->id) ||
			$this->taskValidator->isTaskFollower($command->id)
		){
			return $this->taskRepository->getTaskById($command->id, true);
		}
		else{
			throw new SeException("This task is not for you", 422, 786105);
		}		
	}

}
