<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Illuminate\Auth\Guard;
use Platform\Tasks\Validators\TaskValidator;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\Tasks\Events\SendMailForTaskOwner;
use Platform\Tasks\Events\SendMailWithAttachements;
use Platform\Tasks\Events\SendMailWithAttachementsAndComments;
use Platform\App\RuleCommanding\DefaultRuleBus;

class SendMailForTaskOwnerCommandHandler implements CommandHandler
{
	use EventGenerator;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @var Platform\App\Events\EventDispatcher
	 */
	protected $dispatcher;

	/**
     * @var DefaultRuleBus
     */
    protected $defaultRuleBus;

	/**
	 * @var Illuminate\Auth\Guard
	 */
	protected $auth;

	/**
	 * @var Platform\Tasks\Validators\TaskValidator
	 */
	protected $taskValidator;
	
	/**
	 * @param TaskRepository  $taskRepository 
	 * @param Guard           $auth           
	 * @param TaskValidator   $taskValidator  
	 * @param EventDispatcher $dispatcher     
	 * @param DefaultRuleBus  $defaultRuleBus 
	 */
	public function __construct(
		TaskRepository $taskRepository, 
		Guard $auth, 
		TaskValidator $taskValidator, 
		EventDispatcher $dispatcher, 
		DefaultRuleBus $defaultRuleBus
	){
		$this->taskRepository = $taskRepository;
		$this->auth = $auth;
		$this->taskValidator = $taskValidator;
		$this->dispatcher = $dispatcher;
		$this->defaultRuleBus = $defaultRuleBus;
	}

	/**
	 * @param  SendMailForTaskOwnerCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		$tasks = $this->taskRepository->getTaskById($command->id, true);
		if ($command->type == 'both') {
			$this->defaultRuleBus->setReceiver([$tasks->assignee->email, 'kishan@sourceeasy.com'])
				->setItemURL('hello')
				->execute('SendMailWithAttachementsAndComments1', $tasks);
		}
		elseif ($command->type == 'attachment'){
			$this->defaultRuleBus->setReceiver($tasks->assignee->email)
				->execute('SendMailWithAttachements', $tasks);
		}
		else{
			$this->defaultRuleBus->setReceiver($tasks->assignee->email)
				->execute('SendMailForTaskOwner', $tasks);
		}
		return $tasks;
	}
}
