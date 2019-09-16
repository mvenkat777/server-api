<?php

namespace Platform\CollabBoard\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\CollabBoard\Repositories\Contracts\CollabUserRepository;
use App\CollabUser;

class EloquentCollabUserRepository extends Repository implements CollabUserRepository 
{
    /**
     * Returns the model
     *
     */
	public function model(){
		return 'Platform\CollabBoard\Models\CollabUser';
	}


    /**
     * Adds a sales representative to a collab
     *
     * @param string $collabId
     * @param string $salesRepresentativeId
     */
    public function addSalesRepresentative($collabId, $salesRepresentativeId)
    {
        $data = [
            'id' => $this->generateUUID(),
            'collab_id' => $collabId,
            'user_id' => $salesRepresentativeId,
            'role' => 'sales_representative',
            'is_active' => true,
            'invite_code' => null,
        ];

        return $this->create($data);
    }

    /**
     * Adds a sales representative to a collab
     *
     * @param string $collabId
     * @param string $salesRepresentativeId
     */
    public function addUser($collabId, $userId, $role)
    {
        $exists = $this->model->where('collab_id', $collabId)
                              ->where('user_id', $userId)
                              ->first();
        if (!$exists) {
            $data = [
                'id' => $this->generateUUID(),
                'collab_id' => $collabId,
                'user_id' => $userId,
                'role' => $role,
                'is_active' => false,
                'invite_code' => null,
            ];

            return $this->create($data);
        }
        return false;
    }


}
