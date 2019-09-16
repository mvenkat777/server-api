<?php

namespace Platform\Users\Transformers;

use App\User;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $fractal = new Manager();

        $userDetails = new Item($user->userDetails, new UserDetailsTransformer());
        $userDetails = $fractal->createData($userDetails)->toArray()['data'];

        $userTags = new Collection($user->tags, new UserTagTransformer());
        $userTags = $fractal->createData($userTags)->toArray()['data'];

        $notes = new Collection($user->notes, new UserNoteTransformer());
        $notes = $fractal->createData($notes)->toArray()['data'];

        $response = [
            'id' => (string) $user->id,
            'displayName' => (string) $user->display_name,
            'email' => (string) $user->email,
            'lastLoginLocation' => $user->last_login_location,
            'resetPin' => $user->reset_pin,
            'isActive' => (boolean) $user->is_active,
            'se' => (boolean) $user->se,
            'isPasswordChangeRequired' => $user->is_password_change_required,
            'isBanned' => (boolean) $user->is_banned,
            'createdAt' => $user->created_at->toDateTimeString(),
            'updatedAt' => $user->updated_at->toDateTimeString(),

        ];

        if (isset($userDetails)) {
            $response['userDetails'] = $userDetails;
        }

        if (isset($userTags)) {
            $response['userTags'] = $userTags;
        }

        if (isset($notes)) {
            $response['notes'] = $notes;
        }

        return $response;
    }
}
