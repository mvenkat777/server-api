<?php

namespace Platform\Apps\Transformers;

use League\Fractal\TransformerAbstract;
use App\Permission;

class PermissionTransformer extends TransformerAbstract
{
    public function transform(Permission $perm)
    {
        $data = [
            'permissionId' => (int)$perm->id,
            'permissionName' => (string)$perm->permission
        ];

        return $data;
    }

}
