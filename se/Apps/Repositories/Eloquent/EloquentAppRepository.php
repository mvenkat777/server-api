<?php

namespace Platform\Apps\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Apps\Repositories\Contracts\AppRepository;
use App\Apps;

class EloquentAppRepository extends Repository implements AppRepository
{

	public function model()
	{
		return 'App\Apps';
	}

	public function createApp($data)
	{
		return $this->model->create($data);
	}

	public function allApp()
	{
		return $this->model->all();
	}

	public function updateApp($data)
	{
		return $this->update($data , $data['id']);
	}
	public function getByIdApp($id)
	{
		return $this->model->find($id);
	}

    public function deleteApp($id)
    {
    	return $this->model->where('id','=',$id)->delete();
    }

    public function allPermissions()
	{
		return \App\Permission::all();
	}


}