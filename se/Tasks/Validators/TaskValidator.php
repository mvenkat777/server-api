<?php
namespace Platform\Tasks\Validators;

use Illuminate\Auth\Guard;
use Illuminate\Validation\Factory as Validator;
use Platform\App\Validation\DataValidator;
use Platform\Tasks\Helpers\TaskHelper;
use Platform\Tasks\Repositories\Contracts\TaskFollowerRepository;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Tasks\Repositories\Contracts\TaskStatusRepository;
use Platform\Tasks\Repositories\Eloquent\EloquentTaskStatusRepository;
use Platform\Users\Repositories\Contracts\UserRepository;

class TaskValidator extends DataValidator 
{

	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskStatusRepository
	 */
	protected $taskStatusRepository;

	/**
	 * @var Platform\Tasks\Repositories\Contracts\TaskFollowerRepository
	 */
	protected $taskFollowerRepository;

	/**
	 * @var Platform\Users\Repositories\Contracts\UserRepository
	 */
	protected $userRepository;

	/**
	 * @var Illuminate\Validation\Factory
	 */
	protected $validator;

	/**
	 * @var Illuminate\Auth\Guard
	 */
	protected $auth;

	/**
	 * @var array
	 */
	protected $rules = [];

	/**
	 * @param TaskRepository $taskRepository
	 * @param TaskFollowerRepository $taskFollowerRepository
	 * @param Validator      $validator     
	 * @param Guard          $auth          
	 * @param UserRepository $userRepository
	 */
	public function __construct(TaskRepository $taskRepository,
								TaskFollowerRepository $taskFollowerRepository,
								Validator $validator,
								Guard $auth,
								UserRepository $userRepository,
								EloquentTaskStatusRepository $taskStatusRepository){
		$this->taskRepository = $taskRepository;
		$this->taskStatusRepository = $taskStatusRepository;
		$this->taskFollowerRepository = $taskFollowerRepository;
		$this->userRepository = $userRepository;
		$this->validator = $validator;
		$this->auth = $auth;
	}

	/**
	 * Check if task status is valid or not
	 * 
	 * @param  string  $id
	 * @return boolean    
	 */
	public function isValidTaskStatus($id){
		$status = $this->taskStatusRepository->getTaskStatusById($id);
		if(count($status) < 1){
			return false;
		}
		else{
			return true;
		}
	}

	/**
	 * Check if task is valid or not
	 * 
	 * @param  string  $id
	 * @return boolean    
	 */
	public function isValidTask($id){
		$task = $this->taskRepository->getTaskById($id);
		if(count($task) < 1){
			return false;
		}
		else{
			return true;
		}
	}

	/**
	 * Set input validation rules
	 */
	public function setInputsRules(){
		$this->rules = [
	 		'title' => 'required',
	 		'category' => 'required',
	 		'assignee' => 'required',
	 		'dueDate' => 'required|date',
	 		'priorityId' => 'required|exists:priorities,id'
	 	];

	 	return $this;
	}

	/**
	 * Set upload task validation rules
	 */
	public function setCSVRules()
	{
		$this->rules = [
			"taskFile" => "required|mimes:csv,txt"
		];
		return $this;
	}

	/**
	 * Set validation rules for Add Follow Task
	 */
	public function setFollowTaskRules()
	{
		$this->rules = [
			"followers" => "required|exists:users,email"
		];
		return $this;
	}

	/**
	 * Set validation rules for adding comment
	 */
	public function setCommentRules()
	{
		$this->rules = [
			'type' => 'required',
			'data' => 'required'
		];

		return $this;
	}

	/**
	 * Set validation rules for reassigning task
	 */
	public function setReassignTaskRules()
	{
		$this->rules = [
			'dueDate' => 'sometimes|date',
			'assignee' => 'required'
		];
		return $this;
	}

	/**
	 * Set Validation rule for adding attachment to task
	 */
	public function setAddAttachmentRules()
	{
		$this->rules = [
			// 'data' => 'required'
		];
		return $this;
	}

	/**
	 * @param  string  $id
	 * @return boolean    
	 */
	public function isTaskSubmitted($id){
		$task = $this->taskRepository->getTaskById($id);

		if(!$task->is_submitted){
			return false;
		}
		else{
			return true;
		}
	}

	/**
	 * @param  string  $id
	 * @return boolean    
	 */
	public function isTaskCompleted($id){
		$task = $this->taskRepository->getTaskById($id);
		if(!$task->is_completed){
			return false;
		}
		else{
			return true;
		}
	}

	/**
	 * @param  string  $id
	 * @return boolean    
	 */
	public function isTaskExpired($id){
		$task = $this->taskRepository->getTaskById($id);
		// dd(!$task->status == 'expired');
		if($task->status_id == TaskHelper::getStatusId('expired')){
			return true;
		}
		else{
			return false;
		}
	}

	/**
	 * @param  string  $id
	 * @return boolean    
	 */
	public function isTaskSeen($id){
		$task = $this->taskRepository->getTaskById($id);
		if(is_null($task->seen)){
			return false;
		}
		else{
			return true;
		}
	}

	/**
	 * @param  string  $id
	 * @return boolean    
	 */
	public function isTaskStarted($id){
		$task = $this->taskRepository->getTaskById($id);
		if($task->status_id == TaskHelper::getStatusId('started')){
			return true;
		}
		else{
			return false;
		}
	}

	/**
	 * @param  string  $id
	 * @return boolean    
	 */
	public function isTaskOwner($id){
		$task = $this->taskRepository->getTaskById($id);
		// dd($this->auth->user());
		if($task->creator_id != $this->auth->user()->id){
			return false;
		}
		else{
			return true;
		}
	}

	/**
	 * @param  string  $id
	 * @return boolean    
	 */
	public function isTaskAssignee($id){
		$task = $this->taskRepository->getTaskById($id);
		//also check for isGroup for checking assignee (maybe)
		if($task->assignee_id != $this->auth->user()->id){
			return false;
		}
		else{
			return true;
		}
	}

	/**
	 * Check if user is followerr of task or not
	 * 
	 * @param  string  $id
	 * @return boolean    
	 */
	public function isTaskFollower($id){
		$follower = $this->auth->user()->id;
		return $this->taskFollowerRepository->isFollowerPresent($id, $follower);
	}

	/**
	 * @param  string  $date  
	 * @param  string  $format
	 * @return boolean        
	 */
	public function isValidDate($date, $format = 'Y-m-d H:i:s'){
		$d = \DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

}