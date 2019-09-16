<?php

namespace Platform\Vendor\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Vendor\Repositories\Contracts\VendorTypeRepository;
use App\VendorType;

class EloquentVendorTypeRepository extends Repository implements VendorTypeRepository 
{

	public function model(){
		return 'App\VendorType';
	}

	public function getTypes()
	{
		return $this->model->all();
	}

}