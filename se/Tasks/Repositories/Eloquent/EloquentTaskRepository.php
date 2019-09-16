<?php
namespace Platform\Tasks\Repositories\Eloquent;

use Carbon\Carbon;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Tasks\Helpers\TaskHelper;
use Platform\Tasks\Providers\ConversionProvider;
use Platform\Tasks\Repositories\Contracts\TaskRepository;

class EloquentTaskRepository extends Repository implements TaskRepository{

	public function model(){
		return 'App\Task';
	}

	/**
	 * @param  string  $id
	 * @param  boolean $attach
	 * @return mixed
	 */
	public function getTaskListByUserId($attribute, $value){
		$task = $this->model->where('due_date','<',Carbon::now())
							->where($attribute,$value)
							->whereIn('status_id', [TaskHelper::getStatusId('started'),TaskHelper::getStatusId('assigned')] )
							->get()
							->toArray();
		return $task;
	}

	/**
	 * @param  string  $id
	 * @param  boolean $attach
	 * @return mixed
	 */
	public function getTodayAssignedTaskList($id){
		$task = $this->model->where('due_date','>',Carbon::now())
							->where('assignee_id',$id)
							->where('seen', NULL)
							->where('archived_at', NULL)
							->where('deleted_at', NULL)
							->where('is_submitted', false)
							->where('is_completed', false)
							->where('created_at','>', Carbon::today())
							->get()
							->toArray();
		return $task;
	}

	/**
	 * @param  string  $id
	 * @param  boolean $attach
	 * @return mixed
	 */
	public function getTodaySubmittedTaskList($id){
		$task = $this->model->where('assignee_id', $id)
							->where('archived_at', NULL)
							->where('deleted_at', NULL)
							->where('is_submitted', true)
							->where('is_completed', false)
							->where('submission_date','>', Carbon::today())
							->get()
							->toArray();
		return $task;
	}

	/**
	 * @param  string  $id
	 * @param  boolean $attach
	 * @return mixed
	 */
	public function getTodayCompletedTaskList($id){
		$task = $this->model->where('assignee_id', $id)
							->where('archived_at', NULL)
							->where('deleted_at', NULL)
							->where('is_submitted', true)
							->where('is_completed', true)
							->where('completion_date','>', Carbon::today())
							->get()
							->toArray();
		return $task;
	}

	/**
	 * @param  string  $id
	 * @param  boolean $attach
	 * @return mixed
	 */
	public function getTaskById($id, $attach = false){
		$task = $this->model->where('id', $id)
						->with(['creator','assignee', 'status','comments','attachments'])
						->first();

		return $task;
	}

	/**
	 * Get assigned tasks which are not seen yet
	 *
	 * @param  App\User $user
	 * @return App\Task
	 */
	public function getUnseenAssignedTask($user)
	{
		return $this->getFilterType('assigned', $user->id)
                    ->where('archived_at', null)
					->where('seen', '=', NULL)
					->get();
	}

	/**
	 * Create Task
	 *
	 * @param  CreateUserCommand $command
	 * @param  App\User $user
	 * @return mixed
	 */
	public function createTask($command){
		$data = [
			'id' => $this->generateUUID(),
			'creator_id' => $command->creatorId,
			'title' => $command->title,
			'description' => $command->description,
			'due_date' => $command->dueDate,
			'priority_id' => $command->priorityId,
			'assignee_id' => $command->assignee,
			'status_id' => TaskHelper::getStatusId('assigned'),
			'tna_item_id' => $command->tnaItemId
		];
		try {
            \DB::beginTransaction();
			$task = $this->create($data);
			$this->attach($task->id, 'tags', $command->tags);
			$this->attach($task->id, 'categories', $command->category);
			\DB::commit();
			return $task;
        } catch (\Exception $e) {
            // dd($e);
            throw new \Exception('We are unable to save the task.', "500");
        }
	}

	/**
	 * Get all tasks by type such as assigned,me,submitted,archived
	 *
	 * @param  string $type
	 * @param  App\User $user
	 * @return Collection
	 */
	public function getTaskByType($type, $user, $item=100){
   //      if($type === 'archived'){
			// return $this->model->where(function($query) use($user){
			// 					    $query->where('creator_id', '=', $user->id)
   //                                      ->orWhere('assignee_id', '=', $user->id);
   //                              })
   //                              ->whereNotNull('archived_at')
   //                              ->paginate($item);
   //      }

		$tasks = $this->getFilterType($type, $user->id)
                            ->where('archived_at', null)
							->distinct()
							->select('tasks.*')
							->orderBy('tasks.due_date', 'ASC')
							->paginate($item);

		return $tasks;
	}

	/**
	 * Get all archived tasks by type such as assigned,me,submitted,archived
	 *
	 * @param  string $type
	 * @param  App\User $user
	 * @return Collection
	 */
	public function getArchivedTaskByType($type, $user, $item=100){
		$tasks = $this->getFilterType($type, $user->id)
                            ->whereNotNull('archived_at')
							->distinct()
							->select('tasks.*')
							->orderBy('tasks.due_date', 'ASC')
							->paginate($item);

		return $tasks;
	}

	/**
	 * Filter tasks according to category/dueDate/type
	 *
	 * @param  FilterTaskCommand $command
	 * @param  App\User $user
	 * @return mixed
	 */
	public function filterTasks($command, $user){
        if($command->priorityId !== null) {
			return $this->getFilterType($command->type, $user->id)
							->where('priority_id', $command->priorityId)
							->paginate($command->item);
        }

		if($command->tags != NULL){
			$tasks = $this->getFilterType($command->type, $user->id)
						  ->rightJoin('task_tag_task', 'tasks.id', '=', 'task_tag_task.task_id')
						  ->rightJoin('task_tags',function($join) use($command){
						  	  $join->on('task_tag_task.tag_id','=','task_tags.id')
								   ->whereIn('task_tags.id', $command->tags);
						  })
						  ->distinct()
						  ->select('tasks.*')
						  ->paginate($command->item);

			return $tasks;
		}

		if($command->pending){
			$tasks = $this->getFilterType($command->type, $user->id)
							->where('due_date', '<', Carbon::now())
							->paginate($command->item);

			return $tasks;
		}

		if($command->categories != NULL && $command->date != NULL){
			$tasks = $this->getFilterType($command->type, $user->id)
							->whereBetween('due_date', [$command->date->startDate, $command->date->endDate])
		 					->join('task_category_task', 'tasks.id', '=', 'task_category_task.task_id')
							->join('task_categories',function($join) use($command){
								$join->on('task_category_task.category_id','=','task_categories.id')
										->whereIn('task_categories.id', $command->categories);
							})
							->select('tasks.*')
							->paginate($command->item);
			return $tasks;
		}
		else if($command->categories != NULL && $command->date == NULL){
			$tasks = $this->getFilterType($command->type, $user->id)
						  ->rightJoin('task_category_task', 'tasks.id', '=', 'task_category_task.task_id')
						  ->rightJoin('task_categories',function($join) use($command){
						  	  $join->on('task_category_task.category_id','=','task_categories.id')
								   ->whereIn('task_categories.id', $command->categories);
						  })
						  ->select('tasks.*')
						  ->paginate($command->item);
			return $tasks;
		}
		else if($command->categories == NULL && $command->date != NULL){
			$tasks = $this->getFilterType($command->type, $user->id)
							->whereBetween('due_date', [$command->date->startDate, $command->date->endDate])
							->select('tasks.*')
							->paginate($command->item);
			return $tasks;
		}
		else{
			throw new SeException("Invalid Options", 403);
		}

	}

	/**
	 * Update Task of given id
	 *
	 * @param  UpdateTaskCommand $command
	 * @return mixed
	 */
	public function updateTask($command){
		$data = [
			'title' => $command->title,
			'description' => $command->description,
			'due_date' => $command->dueDate,
			'priority_id' => $command->priority,
			'assignee_id' => $command->assignee
		];
		// try{
			\DB::beginTransaction();
			$this->update($data, $command->id);
			$task = $this->getTaskById($command->id);
			$originalValue = $task->categories->toArray()[0]['title'];
            $result = $task->categories()->sync([$command->category], true);
            if(!empty($result['attached']) || !empty($result['detached']) || !empty($result['updated'])) {
                $task->recordCustomActivity($task, ['categories', [$command->category], false, $originalValue]);
            }
			$task->tags()->sync($command->tags, true);
			\DB::commit();
		// }
		// catch(\Exception $e){
		// 	throw new SeException("Unable to update task", "500");
		// }

		return $task;
	}

	/**
	 * Delete task by id
	 *
	 * @param  DeleteTaskCommand $command
	 * @return boolean
	 */
	public function deleteTask($command){
		return $this->delete($command->id);
	}

	/**
	 * Assign task to an user
	 *
	 * @param  AssignTaskCommand $command
	 * @return mixed
	 */
	public function assignTask($taskId, $email){
		$data = [
			'assignee_id' => $email,
			'status_id' => TaskHelper::getStatusId('assigned')
		];

		$this->update($data, $taskId);
		return $this->getTaskById($taskId);
	}

	/**
	 * Reassign task to an user
	 *
	 * @param  ReassignTaskCommand $command
	 * @return mixed
	 */
	public function reassignTask($command){
		$data = [
			'due_date' => $command->dueDate,
			'assignee_id' => $command->assignee,
			'status_id' => TaskHelper::getStatusId('assigned'),
			'seen' => NULL,
			'is_submitted' => false,
			'is_completed' => false,
			'submission_date' => NULL,
			'completion_date' => NULL,
			'location' => NULL
		];

		$this->update($data, $command->taskId);
		return $this->getTaskById($command->taskId);
	}

	/**
	 * See the task for first time
	 *
	 * @param SeeTaskCommand $command
	 * @return mixed
	 */
	public function seeTask($command){
		$ip = $_SERVER['REMOTE_ADDR'];
		$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		$data = [
			'seen' => Carbon::now(),
			'location' => json_encode($details)
		];
		$this->update($data, $command->taskId);
		return $this->getTaskById($command->taskId);
	}

	/**
	 * Update task status to started
	 *
	 * @param  StartTaskCommand $command
	 * @return mixed
	 */
	public function startTask($taskId, $seeTask = false){
		$data = [
			'status_id' => TaskHelper::getStatusId('started')
		];

        if($seeTask) {
            $data['seen'] = Carbon::now();
        }

		$this->update($data, $taskId);
		return $this->getTaskById($taskId);
	}

	/**
	 * Submit the given task to creator
	 *
	 * @param SubmitTaskCommand $command
	 * @return mixed
	 */
	public function submitTask($command){
		$data = [
			'is_submitted' => true,
			'status_id' => TaskHelper::getStatusId('submitted'),
			'submission_date' => Carbon::now()
		];

        if($command->seeTask) {
            $data['seen'] = Carbon::now();
        }

		$this->update($data, $command->id);
		return $this->getTaskById($command->id);
	}

	/**
	 * Complete the created task
	 *
	 * @param  CompleteTaskCommand $command
	 * @return mixed
	 */
	public function completeTask($command){
		$data = [
			'is_completed' => true,
			'completion_date' => Carbon::now(),
			'status_id' => TaskHelper::getStatusId('completed'),
		];

		try {
            \DB::beginTransaction();
			$this->update($data, $command->id);
			$task = $this->getTaskById($command->id);
			$task->statusNote()->attach([$task->status_id => ['note' => $command->note]]);
			\DB::commit();
			return $task;
        } catch (\Exception $e) {
            throw new \Exception('We are unable to complete the task.', "500");
        }
	}

	/**
	 * Close or Reject Task
	 *
	 * @param  CloseTaskCommand $command
	 * @return mixed
	 */
	public function closeTask($command){
		$data = [
			'status_id' => TaskHelper::getStatusId('assigned'),
			'seen' => NULL,
			'is_submitted' => false,
			'is_completed' => false,
			'submission_date' => NULL,
			//'completion_date' => NULL,
			'location' => NULL
		];

		try {
            \DB::beginTransaction();
			$this->update($data, $command->taskId);
			$task = $this->getTaskById($command->taskId);
			$task->statusNote()->attach([TaskHelper::getStatusId('closed') => ['note' => $command->note]]);
			\DB::commit();
			return $task;
        } catch (\Exception $e) {
            throw new \Exception('We are unable to close the task.', "500");
        }
	}

	/**
	 * Change Priority of Task
	 *
	 * @param  ChangeTaskPriorityCommand $command
	 * @return mixed
	 */
	public function changePriority($command){
		$data = [
			'priority_id' => $command->priorityId
		];
		$this->update($data, $command->taskId);
		return $this->getTaskById($command->taskId);
	}

	/**
	 * Use to get model according to type of task
	 *
	 * @param  string $type
	 * @param  string $userId [UserId]
	 * @param  string $assignee
	 * @return EloquentModel
	 */
	private function getFilterType($type, $userId)
	{
		if($type == 'me'){
			return $this->model->where(function($query) use($userId){
								$query->where('creator_id', '=', $userId)
									->whereIn('status_id', [
										TaskHelper::getStatusId('assigned'),
										TaskHelper::getStatusId('started')
									]);
							});
		}
		elseif ($type == 'assigned') {
			return $this->model->where(function($query) use($userId){
								$query->where('assignee_id', '=', $userId)
									->whereIn('status_id', [
										TaskHelper::getStatusId('assigned'),
										TaskHelper::getStatusId('started')
									]);
							});
		}
		elseif ($type == 'archived') {
			return $this->model->where(function($query) use($userId){
								$query->where('creator_id', '=', $userId)
									->orWhere('assignee_id', '=', $userId);
							})
							->where(function($query){
								$query->where('status_id', '=', TaskHelper::getStatusId('completed'))
									->orWhere('status_id', '=', TaskHelper::getStatusId('closed'));
							});
		}
		elseif($type == 'submitted') {
			return $this->model->where(function($query) use($userId) {
								$query->where('is_submitted', '=', true)
									->where('creator_id', '=', $userId)
									->where('status_id', '=', TaskHelper::getStatusId('submitted'));
							});
		}
		elseif($type == 'all') {
			return $this->model->where(function($query) use($userId) {
									$query->where('creator_id', '=', $userId)
										->orWhere('assignee_id', '=', $userId);
								})
								->whereIn('status_id', [
									TaskHelper::getStatusId('assigned'),
									TaskHelper::getStatusId('started')
								]);
		}
		elseif($type == 'followed'){
			return $this->model->join('task_followers', 'tasks.id', '=', 'task_followers.task_id')
						->where('task_followers.follower_id', '=', $userId)
						->where('task_followers.deleted_at', '=', NULL);
		}
		else{
			return $this->model->where(function($query) use($userId) {
								$query->where('creator_id', '=', $userId)
									->orWhere('assignee_id', '=', $userId)
									->whereNull('deleted_at');
							});
		}

	}

	/**
	 * @param  obj $command
	 * @return string
	 */
	public function addTag($command){
		$task = $this->model->find($command->taskId);
		$task->tags()->sync($command->tag, false);
		return $task;
	}

	/**
	 * @param  obj $command
	 * @return mixed
	 */
	public function removeTag($command){
		$task = $this->model->find($command->taskId);
		$task->tags()->detach($command->tagId, false);
		return $task;
		// return $this->detach($command->id, 'tags', $command->tag);
	}

	public function getTaskRemindeForOverDueDate(){
		$collection = $this->model->whereIn('status_id', [
												TaskHelper::getStatusId('assigned'), 
												TaskHelper::getStatusId('started')
												]
											)
									->where('due_date','<',Carbon::today())
									->get();
		return $collection;
	}

	/**
	 * Get all tasks for review under a user or creator
	 *
	 * @param  App\User $userId
	 * @return Collection
	 */
	public function getCreatorAllTaskForReview($userId)
	{
		return $this->model->where('creator_id', $userId)
							->where('status_id', TaskHelper::getStatusId('submitted'))
							->select('id','title','submission_date','assignee_id')
							->orderBy('submission_date', 'DESC')
							->get();
	}

	/**
	 * Get list of all delegated task of a user or creator
	 *
	 * @param  App\User $userId
	 * @return Collection
	 */
	public function getAllDelegatedTask($userId)
	{
        return $this->getFilterType('me', $userId)->get();
	}

	public function taskFilter($data)
	{
		$item = isset($data['item'])? $data['item'] : config('constants.listItemLimit');
        return $this->filter($data)->paginate($item);
	}
}
