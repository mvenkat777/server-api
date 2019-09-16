<?php

namespace Platform\Techpacks\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

/**
 * Class TechpackTransformer.
 */
class TechpackUserTransformer extends TransformerAbstract
{
    /**
     * @param Techpack $techpack
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'displayName' => $user->display_name,
            'email' => (string) $user->email,
            'lastLoginLocation' => $user->last_login_location
        ];
    }
}
