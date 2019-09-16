<?php

namespace Platform\Vendor\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Vendor\Repositories\Contracts\VendorPaymentTermRepository;
use App\VendorPaymentTerm;

class EloquentVendorPaymentTermRepository extends Repository implements VendorPaymentTermRepository 
{

	public function model(){
		return 'App\VendorPaymentTerm';
	}

	public function getPaymentTerms()
	{
		return $this->model->all();
	}
}