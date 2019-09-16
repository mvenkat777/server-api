<?php

namespace Platform\Vendor\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Vendor\Repositories\Contracts\VendorCapabilityRepository;
use App\VendorCapability;

class EloquentVendorCapabilityRepository extends Repository implements VendorCapabilityRepository 
{

	public function model(){
		return 'App\VendorCapability';
	}

	public function getCapabilities()
	{
		return $this->model->all();
	}
}