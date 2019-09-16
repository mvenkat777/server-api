<?php

namespace Platform\Dashboard\Repositories\Eloquent;

use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Dashboard\Repositories\Contracts\DashboardRepository;

class EloquentDashboardRepository extends Repository implements DashboardRepository 
{

	/**
	 * Get Namespace of Model
	 * @return string 
	 */
	public function model(){
		return 'App\User';
	}

    public function getAppFeed() 
    {
    }

}
