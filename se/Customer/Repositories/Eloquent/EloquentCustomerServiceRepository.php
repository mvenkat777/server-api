<?php

namespace Platform\Customer\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Customer\Repositories\Contracts\CustomerServiceRepository;
use App\CustomerService;

class EloquentCustomerServiceRepository extends Repository implements CustomerServiceRepository 
{

	public function model(){
		return 'App\CustomerService';
	}

	public function getServices()
	{
		return $this->model->all();
	}
}