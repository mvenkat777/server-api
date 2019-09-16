<?php

namespace Platform\Priority\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Priority\Repositories\Contracts\PriorityRepository;
use App\Priority;

class EloquentPriorityRepository extends Repository implements PriorityRepository
{

	public function model()
	{
		return 'App\Priority';
	}

	public function createPriority($data)
	{
		return $this->model->create($data);
	}

	public function allPriority()
	{
		return $this->model->all();
	}

	public function updatePriority($data)
	{
		return $this->update($data , $data['id']);
	}
	public function getByIdPriority($id)
	{
		return $this->model->find($id);
	}

    public function deletePriority($id)
    {
    	return $this->model->where('id','=',$id)->delete();
    }


}