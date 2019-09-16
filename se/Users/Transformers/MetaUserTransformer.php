<?php

namespace Platform\Users\Transformers;

use App\User;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Users\Transformers\UserDetailsTransformer;
use Platform\Users\Transformers\UserNoteTransformer;
use Platform\Users\Transformers\UserTagTransformer;

class MetaUserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => (string)$user->id,
            'displayName' => (string)$user->display_name,
            'email' => (string)$user->email,
            'lastLoginLocation' => $user->last_login_location     
        ];

    }
}
