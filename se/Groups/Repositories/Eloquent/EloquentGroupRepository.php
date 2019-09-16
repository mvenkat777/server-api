<?php

namespace Platform\Groups\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Groups\Repositories\Contracts\GroupRepository;
use App\Group;
use Platform\App\Exceptions\SeException;

class EloquentGroupRepository extends Repository implements GroupRepository
{

    public function model()
    {
        return 'App\Group';
    }

    public function createGroup($data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            throw new SeException("Group Name already exists. Please try again", 409, 7310409); 
        }
    }

    public function allGroup()
    {
        return $this->model->all();
    }

    public function updateGroup($data)
    {
        try {
            return $this->update($data, $data['id']);
        } catch (\Exception $e) {
            throw new SeException("Group Name already exists. Please try again", 409, 7310409);
        }
    }
    public function getByIdGroup($id)
    {
        return $this->model->find($id);
    }

    public function getByGroupName($groupName)
    {
        return $this->findBy('name', $groupName);
    }

    public function deleteGroup($id)
    {
        return $this->model->where('id', '=', $id)->delete();

    }
}
