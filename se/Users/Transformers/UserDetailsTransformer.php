<?php

namespace Platform\Users\Transformers;

use League\Fractal\TransformerAbstract;
use App\UserDetail;

class UserDetailsTransformer extends TransformerAbstract
{
    public function transform(UserDetail $userDetail)
    {
        return [
            'userId' => (string)$userDetail->user_id,
            'firstName' => (string)$userDetail->first_name,
            'lastName' => (string)$userDetail->last_name,
            'country' => $userDetail->country,
            'city' => $userDetail->city,
            'state' => $userDetail->state,
            'mobileNumber' => $userDetail->mobile_number,
            'location' => json_decode($userDetail->location),
            'createdAt' => $userDetail->created_at->toDateTimeString(),
            'updatedAt' => $userDetail->updated_at->toDateTimeString()
        ];
    }
}
