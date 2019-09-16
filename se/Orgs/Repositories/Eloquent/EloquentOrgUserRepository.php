<?php

namespace Platform\Orgs\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Orgs\Repositories\Contracts\OrgUserRepository;
use App\Org;

class EloquentOrgUserRepository extends Repository implements OrgUserRepository
{

	public function model()
	{
		return 'App\RoleUser';
	}

	public function createRoleUser($data)
	{
		return $this->model->create($data);
	}



}