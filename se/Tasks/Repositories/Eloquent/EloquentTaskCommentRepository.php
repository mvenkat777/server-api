<?php

namespace Platform\Tasks\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Tasks\Repositories\Contracts\TaskCommentRepository;
use App\TaskComment;

class EloquentTaskCommentRepository extends Repository implements TaskCommentRepository 
{

	public function model(){
		return 'App\TaskComment';
	}

	/**
	 * @param object $command
	 * @return comment
	 */
	public function add($command)
	{
		$data = [
			'id' => $this->generateUUID(),
			'task_id' => $command->taskId,
			'creator_id' => $command->owner,
			'type' => $command->type,
			'data' => $command->data
		];

		return $this->create($data);
	}

	/**
	 * @param  string $id
	 * @return number of rows deleted  
	 */
	public function deleteComment($id)
	{
		return $this->delete($id);
	}

	/**
	 * @param  string $taskId 
	 * @return number of rows deleted        
	 */
	public function deleteForTasks($taskId)
	{
		return $this->model->where('task_id', '=', $taskId)
							->delete();
	}

	/**
	 * @param  string $taskId 
	 * @return Comment       
	 */
	public function getCommentsByTaskId($taskId)
	{
		return $this->model->where('task_id', '=', $taskId)
			->orderBy('created_at','desc')
			->paginate(5);
	}
}