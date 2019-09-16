<?php

namespace Platform\GlobalFilter\Repositories\Eloquent;

use Illuminate\Support\Facades\Hash;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\GlobalFilter\Commands\ShowAppEntityByAppNameCommand;
use Platform\GlobalFilter\Repositories\Contracts\AppEntityRepository;
use Vinkla\Hashids\HashidsManager;



class EloquentAppEntityRepository extends Repository implements AppEntityRepository
{ 

    public function model()
    {
        return 'App\Task';
    }


    

}
