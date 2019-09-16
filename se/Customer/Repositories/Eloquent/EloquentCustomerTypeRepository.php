<?php

namespace Platform\Customer\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Customer\Repositories\Contracts\CustomerTypeRepository;
use App\CustomerType;

class EloquentCustomerTypeRepository extends Repository implements CustomerTypeRepository 
{

	public function model(){
		return 'App\CustomerType';
	}

	public function getTypes()
	{
		return $this->model->all();
	}

}