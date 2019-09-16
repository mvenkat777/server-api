<?php

namespace Platform\Roles\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Roles\Repositories\Contracts\RoleRepository;
use App\Role;
use Platform\App\Exceptions\SeException;

class EloquentRoleRepository extends Repository implements RoleRepository
{

    public function model()
    {
        return 'App\Role';
    }

    public function createRole($data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            //dd($e);
            throw new SeException("Unable to save. Please check if role exists in the same group and try again", 409, 7310409); 
        }
    }

    public function allRole()
    {
        return $this->model->all();
    }

    public function updateRole($data)
    {
        try {
            return $this->update($data, $data['id']);
        } catch (\Exception $e) {
            throw new SeException("Unable to save. Please check if role exists in the same group and try again", 409, 7310409); 
        }
    }
    public function getByIdRole($id)
    {
        return $this->model->find($id);
    }

    public function getRolesByGroupId($gid)
    {
        return $this->model->where('group_id', '=', $gid)->get();
    }

    public function deleteRole($id)
    {
        return $this->model->where('id', '=', $id)->delete();
    }
}
