<?php

namespace Platform\Orgs\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Orgs\Repositories\Contracts\OrgRepository;
use App\Org;

class EloquentOrgRepository extends Repository implements OrgRepository
{

	public function model()
	{
		return 'App\Org';
	}

	public function createOrg($data)
	{
		return $this->model->create($data);
	}

	public function allOrg()
	{
		return $this->model->all();
	}

	public function updateOrg($data)
	{
		return $this->update($data , $data['id']);
	}
	public function getByIdOrg($id)
	{
		return $this->model->find($id);
	}

    public function deleteOrg($id)
    {
    	return $this->model->where('id','=',$id)->delete();
    }


}