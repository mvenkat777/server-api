<?php

namespace Platform\Groups\Transformers;

use App\Group;
use League\Fractal\TransformerAbstract;

class GroupTransformer extends TransformerAbstract
{
    public function transform(Group $group)
    {
        $data = [
            'groupId' => $group->id,
            'groupName' => (string) $group->name,
            'groupDescription' => (string) $group->description,
            'ownerEmail' => (string) ($group->owner_email)
        ];

        if (isset($group->status)) {
            $data['status'] = (string) $group->status;
        }

        if (isset($group->pivot->userId)) {
            $data['userId'] = (string) $group->pivot->user_id;
        }

        return $data;
    }
}
