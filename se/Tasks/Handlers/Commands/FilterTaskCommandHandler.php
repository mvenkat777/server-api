<?php 

namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Illuminate\Auth\Guard;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Repositories\Contracts\CategoryRepository;
use Platform\Tasks\Repositories\Contracts\TaskFollowerRepository;
use Platform\Tasks\Validators\TaskValidator;
use Carbon\Carbon;
use Platform\Tasks\Helpers\TaskHelper;

class FilterTaskCommandHandler implements CommandHandler
{
	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskFollowerRepository
	 */
	protected $taskFollowerRepository;

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
	 * @param TaskRepository     $taskRepository     
	 * @param Guard              $auth               
	 * @param CategoryRepository $categoryRepository 
	 * @param TaskValidator      $taskValidator      
	 * @param TaskHelper         $taskHelper         
	 */
	public function __construct(TaskRepository $taskRepository,
								TaskFollowerRepository $taskFollowerRepository,
								Guard $auth,
								CategoryRepository $categoryRepository,
								TaskValidator $taskValidator,
								TaskHelper $taskHelper)
	{
		$this->taskRepository = $taskRepository;
		$this->taskFollowerRepository = $taskFollowerRepository;
		$this->auth = $auth;
		$this->categoryRepository = $categoryRepository;
		$this->taskValidator = $taskValidator;
		$this->taskHelper = $taskHelper;
	}

	/**
	 * @param  FilterTaskCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		if(!is_null($command->date)){
			if(!$this->taskValidator->isValidDate($command->date->startDate) 
				|| !$this->taskValidator->isValidDate($command->date->endDate)){
				throw new SeException("Invalid Date", 422, 786106);
			}
			
			if($command->date->startDate > $command->date->endDate){
				throw new SeException('Start date is greater than End date', 422, 786107);
			}
		}

		$command->categories = $this->taskHelper->changeCategoryForFilter($command->categories);
		$command->tags = $this->taskHelper->changeTagsForFilter($command->tags);
		
		$tasks = $this->taskRepository->filterTasks($command, $this->auth->user());
		if(count($tasks) < 1){
			throw new SeException("No Tasks Found", 404, 786104);
		}
		return $tasks;
	}

}
