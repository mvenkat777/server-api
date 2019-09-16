<?php

namespace Platform\Roles\Transformers;

use League\Fractal\TransformerAbstract;
use App\Role;

class RoleTransformer extends TransformerAbstract
{
    public function transform(Role $role)
    {
        $data = [
            'roleId' => (int)$role->id,
            'roleName' => (string)$role->name,
            'roleDescription' => (string)$role->description
        ];
        if(isset($role->status)){
            $data['status'] = (string)$role->status;
        }

        if(isset($role->group_id)){
            $data['groupId'] = (int)$role->group_id;
        }

        if(isset($role->apps_permissions)){
            $data['appPermission'] = json_decode($role->apps_permissions);
        }

        if(isset($role->pivot->userId)){
        	$data['userId'] = (string)$role->pivot->userId;
        }
        // if(isset($role->pivot->permission)){
        // 	$data['permission'] = (string)$role->pivot->permission;
        // }

        return $data;
    }

}
