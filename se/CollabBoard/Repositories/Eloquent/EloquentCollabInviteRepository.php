<?php

namespace Platform\CollabBoard\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\CollabBoard\Repositories\Contracts\CollabInviteRepository;
use App\CollabInvite;
use Platform\App\Helpers\Helpers;

class EloquentCollabInviteRepository extends Repository implements CollabInviteRepository 
{
    /**
     * Get COllabInvite model
     */
	public function model() {
		return 'Platform\CollabBoard\Models\CollabInvite';
	}

    /**
     * Get invited user by collabId and userId
     *
     * @param string $collabId
     * @param string $userId
     */
    public function getByCollabIdAndUserId($collabId, $userId) {
        return $this->model->where('collab_id', $collabId)
                           ->where('user_id', $userId)
                           ->first();
    }

    /**
     * Invite user to collab
     *
     * @param string $collabId
     * @param string $userId
     */
    public function inviteUser($collabId, $userId, $permission) {
        $data = [
            'id' => $this->generateUuid(),
            'collab_id' => $collabId,
            'user_id' => $userId,
            'permission' => $permission,
            'invite_code' => Helpers::generateRandomCode($collabId . $userId . $permission),
            'is_active' => false,
        ];

        return $this->model->create($data);
    }

    /**
     * Get invited user by collabId and invite code
     *
     * @param string $collabId
     * @param string $inviteCode
     */
    public function getByCollabIdAndInviteCode($collabId, $inviteCode) {
        return $this->model->where('collab_id', $collabId)
                           ->where('invite_code', $inviteCode)
                           ->first();
    }

    /**
     * Accpet an invite
     *
     * @param Platform\CollabBoard\Models\CollabInvite $invite
     */
    public function accept($invite)
    {
        $invite->is_active = true;
        $invite->invite_code = null;
        return $invite->update();
    }

}
