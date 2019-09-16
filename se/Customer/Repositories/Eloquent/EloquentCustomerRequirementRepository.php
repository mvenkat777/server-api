<?php

namespace Platform\Customer\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Customer\Repositories\Contracts\CustomerRequirementRepository;
use App\CustomerRequirement;

class EloquentCustomerRequirementRepository extends Repository implements CustomerRequirementRepository 
{

	public function model(){
		return 'App\CustomerRequirement';
	}

	public function getRequirements()
	{
		return $this->model->all();
	}

}