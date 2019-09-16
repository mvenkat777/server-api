<?php

namespace Platform\Tasks\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Tasks\Repositories\Contracts\TaskFollowerRepository;
use App\TaskFollower;

class EloquentTaskFollowerRepository extends Repository implements TaskFollowerRepository 
{

	public function model(){
		return 'App\TaskFollower';
	}

	/**
	 * @param array $dataArray
	 * @return number
	 */
	public function add($dataArray)
	{
		$data = [
			'id' => $this->generateUUID(),
			'task_id' => $dataArray['taskId'],
			'follower_id' => $dataArray['follower']
		];
		
		return $this->model->create($data);
	}

	public function deleteFollower($id)
	{
		return $this->delete($id);
	}

	/**
	 * Force delete all followers of task in order to fresh add
	 * @param  string $id [taskId]
	 * @return number     [Number of rows deleted]
	 */
	public function forceDeleteAll($id)
	{
		return $this->model->where('task_id', '=', $id)
							->forceDelete();
	}

	public function deleteForTasks($taskId)
	{
		return $this->model->where('task_id', '=', $taskId)
							->delete();
	}

	/**
	 * @param  App\User $user
	 * @return App\TaskFollower      
	 */
	public function getFollowedTasks($user)
	{
		$followers = $this->model->where('follower_id', '=', $user->id)
								->lists('task_id');

		return $followers;
	}

	/**
	 * @param  string  $taskId  
	 * @param  string  $follower
	 * @return boolean          
	 */
	public function isFollowerPresent($taskId, $follower)
	{
		return	$this->model->where('task_id', '=', $taskId)
							->where('follower_id', '=', $follower)
							->count();
	}

}