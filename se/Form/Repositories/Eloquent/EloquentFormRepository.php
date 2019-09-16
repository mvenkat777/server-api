<?php

namespace Platform\Form\Repositories\Eloquent;

use Platform\Form\Model\FormUser;
use Carbon\Carbon;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Form\Repositories\Contracts\FormRepository;

/**
* 
*/
class EloquentFormRepository extends Repository implements FormRepository
{
    
    /**
     * @return \Platform\Form\Model\FormUser
     */
    public function model()
    {
        return 'Platform\Form\Model\FormUser';
    }

    public function addForm($value)
    {
        \DB::beginTransaction(); 
            $value['id'] = $this->generateUUID();
            return $this->create($value);
        \DB::commit();
    }

    public function get($value)
    {
        
    }
}