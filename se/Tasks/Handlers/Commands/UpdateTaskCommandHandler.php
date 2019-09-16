<?php 

namespace Platform\Tasks\Handlers\Commands;

use Carbon\Carbon;
use App\Task;
use Illuminate\Auth\Guard;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Commands\AddAttachmentCommand;
use Platform\Tasks\Commands\CreateTagCommand;
use Platform\Tasks\Commands\UpdateAttachmentCommand;
use Platform\Tasks\Events\UpdateLog;
use Platform\Tasks\Helpers\TaskHelper;
use Platform\Tasks\Providers\ConversionProvider;
use Platform\Tasks\Repositories\Contracts\CategoryRepository;
use Platform\Tasks\Repositories\Contracts\TagRepository;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Validators\TaskValidator;
use Platform\Users\Repositories\Contracts\UserRepository;

class UpdateTaskCommandHandler implements CommandHandler
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
	 * @var Platform\Tasks\Helpers\TaskHelper
	 */
	protected $taskHelper;

	/**
     * @var DefaultRuleBus
     */
    protected $defaultRuleBus;

    /**
     * @var Platform\Users\Repositories\Contracts\UserRepository
     */
    protected $userRepo;
	
	/**
	 * @param DefaultCommandBus $commandBus    
	 * @param EventDispatcher   $dispatcher    
	 * @param TaskRepository    $taskRepository
	 * @param Guard             $auth          
	 * @param TaskValidator     $taskValidator 
	 * @param TaskHelper        $taskHelper    
	 */
	public function __construct(
		DefaultCommandBus $commandBus,
		TaskRepository $taskRepository,
		Guard $auth,
		TaskValidator $taskValidator,
		DefaultRuleBus $defaultRuleBus,
		TaskHelper $taskHelper,
		UserRepository $userRepo
	){
		$this->commandBus = $commandBus;
		$this->taskRepository = $taskRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
		$this->taskHelper = $taskHelper;
		$this->userRepo = $userRepo;
		$this->defaultRuleBus = $defaultRuleBus;
	}

	/**
	 * @param  UpdateTaskCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		// if($command->dueDate < Carbon::now()->toDateTimeString()){
		// 	throw new SeException('Due date is less than today', 422, 786108);
		// }

		// if($this->taskValidator->isTaskOwner($command->id)
		// 	 || $this->taskValidator->isTaskAssignee($command->id)
		// 	 || $this->taskValidator->isTaskFollower($command->id)){
			$details = Task::where('id', $command->id)->first();
			$command->tags = ConversionProvider::convertToTagTitles($command->tags);

			$command->tags = $this->taskHelper->changeToTagId($command->tags);
			$command->category = $this->taskHelper->changeToCategoryId($command->category);
			
			$task = $this->taskRepository->updateTask($command, $this->auth->user());
			
            /*
			foreach ($command->attachments as $attachment) {
				$attachment = $this->commandBus->execute(new UpdateAttachmentCommand($attachment['data'], $task->id, $attachment['type'], $attachment));	
			}
             */
			if(Carbon::parse($command->dueDate) != Carbon::parse($details->due_date)) {
				$receivers = TaskHelper::getTaskNotificationReceivers($task, \Auth::user()->id);
				//$this->notify($receivers, $task);
			}

			return $this->taskRepository->getTaskById($task->id, true);
		// }
		// throw new SeException("This task does not belong to you", 401, 786105);
	}

	/**
	 * @param  array $receivers 
	 * @param  Task $task      
	 */
	public function notify($receivers , $task)
	{
		foreach ($receivers as $key => $receiverId) {
			$receiver = $this->userRepo->userById($receiverId);
			if($receiver){
				$this->defaultRuleBus->setReceiver($receiver->email)
						->execute('DueDateIsUpdatedToTask', $task);
			}
		}
	}
}
