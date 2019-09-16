<?php
namespace Platform\Tasks\Handlers\Commands;

use Illuminate\Auth\Guard;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Commands\CreateTaskCommand;
use Platform\Tasks\Helpers\TaskHelper;
use Platform\Tasks\Providers\ConversionProvider;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Validators\TaskValidator;
use Platform\Tasks\Validators\UploadTask;

class UploadTasksCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\App\Commanding\DefaultCommandBus
	 */
	protected $commandBus;

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
	 * @var Platform\Tasks\Validators\UploadTask
	 */
	protected $uploadTask;
	
	/**
	 * @param DefaultCommandBus $commandBus    
	 * @param TaskRepository    $taskRepository
	 * @param Guard             $auth          
	 * @param TaskValidator     $taskValidator 
	 * @param UploadTask        $uploadTask    
	 */
	public function __construct(DefaultCommandBus $commandBus,
								TaskRepository $taskRepository,
								Guard $auth,
								TaskValidator $taskValidator,
								UploadTask $uploadTask)
	{
		$this->commandBus = $commandBus;
		$this->taskRepository = $taskRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
		$this->uploadTask = $uploadTask;
	}

	/**
	 * @param  UploadTaskCommmand $command
	 * @return mixed         
	 */
	public function handle($command)
	{
		$result = \Excel::load($command->taskFile)->get();
		
		$tasks = $result->toArray();
		$error = [];
		$success = [];
		// array_map('validateAndCreateTask', $tasks);
		foreach ($tasks as $key => $task) {
			$this->uploadTask->validate($task);
			$priorityId = TaskHelper::getPriorityId($task['priority']);
			if(!$priorityId){
				$error[] = json_decode('{"message":"Invalid Priority at line '.($key+2).'"}');
				continue;
			}
			$task['priority'] = $priorityId;
			$task = ConversionProvider::convertToQuickTask($task);
			$task['row'] = $key+2;
			$task['byUpload'] = true;
			
			$result = $this->commandBus->execute(new CreateTaskCommand($task));
			
			if(is_object($result)){
				$success[] = $result;
			}
			else{
				$error[] = json_decode('{"message":"'.$result.'"}');
			}
		}

		$response = ['tasks' => count($success).' tasks created', 'error' => $error];
		return $response;
	}

}