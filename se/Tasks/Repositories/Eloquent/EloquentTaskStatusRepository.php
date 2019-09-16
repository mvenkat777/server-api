<?php
namespace Platform\Tasks\Repositories\Eloquent;

use Platform\Tasks\Repositories\Contracts\TaskStatusRepository;
use Platform\App\Repositories\Eloquent\Repository;

class EloquentTaskStatusRepository extends Repository implements TaskStatusRepository{

	public function model(){
		return 'App\TaskStatus';
	}

	/**
	 * Get TaskStatus by id
	 * 
	 * @param  string $id
	 * @return App\TaskStatus
	 */
	public function getTaskStatusById($id){
		return $this->model->where('id', '=', $id)->first();
	}

	/**
	 * Get TaskStatus by title
	 * 
	 * @param  string $title
	 * @return App\TaskStatus
	 */
	public function getTaskStatusByTitle($title){
		return $this->model->where('status', '=', $title)->first();
	}

	/**
	 * Get all TaskStatus
	 * 
	 * @return Collection
	 */
	public function getAllTaskStatus(){
		return $this->all();
	}

	public function createTaskStatus($title)
	{
		$data = [
			'status' => $title
		];

		$this->create($data);
		return $this->getTaskStatusByTitle($title);
	}

}