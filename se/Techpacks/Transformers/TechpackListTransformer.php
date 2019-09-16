<?php

namespace Platform\Techpacks\Transformers;

use App\Techpack;
use App\User;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Techpacks\Transformers\TechpackUserTransformer;
use Platform\Users\Transformers\MetaUserTransformer;

class TechpackListTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform(Techpack $techpack)
	{
        $meta = $techpack->meta;

        if (isset($meta->owner)) {
            $lastLoginLocation = $techpack->users->toArray()[0]['last_login_location'];
            $meta->owner->lastLoginLocation = $lastLoginLocation;
        } else {
            $user = \App\User::find($techpack->user_id);
            $meta->owner = (new TechpackUserTransformer())->transform($user);
        }
        $meta->lockedAt = !is_null($techpack->locked_at) ? date(DATE_ISO8601, strtotime($techpack->locked_at)) : null;
        $meta->lockedBy = !is_null($techpack->locked_by) ? (new MetaUserTransformer)->transform(\App\User::find($techpack->locked_by)) : NULL;
        $meta->unlockedBy = !is_null($techpack->unlocked_by) ? (new MetaUserTransformer)->transform(\App\User::find($techpack->unlocked_by)) : NULL;
        $meta->unlockedAt = !is_null($techpack->unlocked_at) ? date(DATE_ISO8601, strtotime($techpack->unlocked_at)) : null;
        $response = [
            'id' => $techpack->id,
            'meta' => $meta,
            'updatedAt' => $techpack->updated_at->toDateTimeString(),
            'completedAt' => is_null($techpack->completed_at)? NULL : $techpack->completed_at->toDateTimeString(),
            'archivedAt' => is_object($techpack->archived_at) ? $techpack->archived_at->toDateTimeString() : NULL
        ];
        $response['meta']->isEditable = (boolean) $this->isEditable($techpack);

        return $response;
	}

	/**
     * Add Techpack Editable permission as per user
     * @param array $techpacks
     */
    public function isEditable($techpack)
    {
        $role = \App\Role::where('name', 'Edit Access')->first();
        $userIds = is_null($role)? [] : $role->users->lists('id')->toArray();

        if (empty($userIds)) {
            return (
                $techpack->user_id === \Auth::user()->id ||
                \Auth::user()->is_god === true
            );
        }
        return (
            $techpack->user_id === \Auth::user()->id ||
            in_array(\Auth::user()->id, $userIds)  ||
            \Auth::user()->is_god === true
        );
    }

}
