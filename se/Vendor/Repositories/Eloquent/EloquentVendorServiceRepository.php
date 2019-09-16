<?php

namespace Platform\Vendor\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Vendor\Repositories\Contracts\VendorServiceRepository;
use App\VendorService;

class EloquentVendorServiceRepository extends Repository implements VendorServiceRepository 
{

	public function model(){
		return 'App\VendorService';
	}

	public function getServices()
	{
		return $this->model->all();
	}

}