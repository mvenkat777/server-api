<?php

namespace Platform\Roles\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Roles\Repositories\Contracts\RoleUserRepository;
use App\Role;
use App\User;
use Platform\App\Exceptions\SeException;

class EloquentRoleUserRepository extends Repository implements RoleUserRepository
{

    public function model()
    {
        return 'App\Role';
    }

    public function createRoleUser($data)
    {
        return $this->model->create($data);
    }
    public function getUsersByRoles($role)
    {
        $roleId = $this->getRoleById($role);
        //dd($roleId);
        if ($roleId != null) {
            return $roleId->users()->get();
        } else {
            throw new SeException('Role not found', 404, 7310404);
        }
    }

    public function allUserRoles($id)
    {
        $user = User::find($id);
        return $user->roles;
    }

    public function getUnassignedUsers($role)
    {
        $roleUsers = $this->getUsersByRoles($role);
        //dd($roleUsers->toArray());
        $roleUserIds = [];
        foreach($roleUsers->toArray() as $roleUsersData){
            $roleUserIds[] = $roleUsersData['id'];
        }

        $conditions = ['is_banned' => false , 'is_active' => true ];
        $users = User::where($conditions)->whereNotIn('id', $roleUserIds)->get();
        return $users;
    }

    public function getRoleById($role)
    {
        return Role::find($role);
    }

    public function deleteUsersByRole($users, $role)
    {
        Role::find($role)->users()->detach($users);
        return true;
    }

    public function addUsersToRole($users, $role)
    {
        //dd($data);
        try {
            //dd(User::find($data['user_id'])->roles());
            Role::find($role)->users()->attach($users);
            return true;
            //return $this->model->create($data);
        } catch (\Exception $e) {
            throw new SeException('User(s) already exist.. Please try again', 321, 3210113);
        }
    }
}
