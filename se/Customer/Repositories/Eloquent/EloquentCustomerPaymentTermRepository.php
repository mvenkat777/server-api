<?php

namespace Platform\Customer\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Customer\Repositories\Contracts\CustomerPaymentTermRepository;
use App\CustomerPaymentTerm;

class EloquentCustomerPaymentTermRepository extends Repository implements CustomerPaymentTermRepository 
{

	public function model(){
		return 'App\CustomerPaymentTerm';
	}

	public function getPaymentTerms()
	{
		return $this->model->all();
	}

}