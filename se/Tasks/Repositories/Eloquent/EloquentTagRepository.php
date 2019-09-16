<?php
namespace Platform\Tasks\Repositories\Eloquent;

use Platform\Tasks\Repositories\Contracts\TagRepository;
use Platform\App\Repositories\Eloquent\Repository;

class EloquentTagRepository extends Repository implements TagRepository{

	public function model(){
		return 'App\TaskTag';
	}

	public function getTagByTitle($title){
		return $this->model->where('title', '=', $title)->first();
	}

	public function createTag($command){
		$data = [
			'id' => $this->generateUUID(),
			'title' => $command->title
		];

		return $this->create($data);
	}

	public function getAllTags(){
		return $this->all();
	}

}