<?php

namespace Platform\Users\Transformers;

use League\Fractal\TransformerAbstract;
use App\UserUserTag;

class UserUserTagTransformer extends TransformerAbstract
{
    public function transform(UserUserTag $userTag)
    {
        return [
            'id' => $userTag->id,
            'userId' => (string)$userTag->user_id,
            'taggedBy' => (string)$userTag->tagged_by,
            'tagId' => $userTag->tag_id,
            'createdAt' => $userTag->created_at->toDateTimeString(),
            'updatedAt' => $userTag->updated_at->toDateTimeString()
        ];
    }
}
