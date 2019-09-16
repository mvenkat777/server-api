<?php
namespace Platform\Tasks\Repositories\Eloquent;

use Platform\Tasks\Repositories\Contracts\CategoryRepository;
use Platform\App\Repositories\Eloquent\Repository;

class EloquentCategoryRepository extends Repository implements CategoryRepository{

	public function model(){
		return 'App\TaskCategory';
	}

	/**
	 * Get Category by title
	 * 
	 * @param  string $title
	 * @return App\Category
	 */
	public function getCategoryByTitle($title){
		return $this->model->where('title', '=', $title)->first();
	}

	/**
	 * Get all categories
	 * 
	 * @return Collection
	 */
	public function getAllCategories(){
		return $this->all();
	}

	public function createCategory($title)
	{
		$data = [
			'id' => $this->generateUUID(),
			'title' => $title
		];

		$this->create($data);
		return $this->getCategoryByTitle($title);
	}

}