<?php

namespace Platform\Users\Repositories\Eloquent;

use App\Note;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Users\Repositories\Contracts\TagRepository;

class EloquentTagRepository extends Repository implements TagRepository
{

    public function model()
    {
        return 'App\UserTag';
    }

    public function createTag($data)
    {
    	 $tag = [
            'name' => $data->name
        ];
        // dd('fdd');
        return $this->model->create($tag);
    	
    }

    public function getTag($name)
    {
    	return $this->model->where('name','=',$name)->first();
    }

    public function getAll($command)
    {
    	return $this->model->get();
    }

    public function search($command)
    {
                
        $data = $this->model->where('name','=',$command->tag)
                            ->with('users')
                            ->paginate($command->page);
        
        foreach ($data as $value) {
            foreach($value->users as $user){
                $user->tags;
            }
        }

        return $data;
    }
}
