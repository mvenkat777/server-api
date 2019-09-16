<?php

namespace Platform\Picks\Repositories\Eloquent;

use Illuminate\Support\Facades\Auth;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Picks\Repositories\Contracts\PickRepository;
use Platform\App\Helpers\Helpers;

class EloquentPickRepository extends Repository implements PickRepository
{
	/**
	 * Get Pick Model
	 * @return string
	 */
    public function model()
    {
        return 'Platform\Picks\Models\Pick';
    }

    /**
     * Get a pick using id with all the relations
     *
     * @param mixed $id
     */
    public function getByIdWithRelations($id)
    {
        return $this->model->with(['boards', 'uploader'])
                           ->find($id); 
    }

	/**
	 * Create a new pick
	 *
	 * @param  array $data
	 * @return App\Pick
	 */
    public function createPick($data)
    {
        $data = [
            'id' => $this->generateUUID(),
            'name' => $data['name'],
            'pick' => json_encode($data['pick']),
            'uploader_id' => Helpers::getAuthUserId(),
        ];

    	return $this->model->create($data);
    }

    /**
     * Get pick based on pickId
     *
     * @param  string $pickId
     * @return App\Pick
     */
    public function getById($pickId)
    {
    	return $this->find($pickId);
    }


    /**
     * Check if authenticated  user it the owner of pick
     *
     * @param  string  $pickId
     * @param  string  $userId
     * @return boolean
     */
    public function isOwner($pickId, $userId)
    {
        $pick = $this->find($pickId);

        if ($pick) {
            return $pick->ownerId === $userId;
        }

        return false;
    }

    /**
     * Delete pick based on pickId
     *
     * @param  string $pickId
     * @return mixed
     */
    public function deletePick($pickId)
    {
        return $this->delete($pickId);
    }

    /**
     * Update pick
     *
     * @param  string  $pickId
     * @return array    $data
     */
    public function updatePick($pickId, $data)
    {
        $pick = $this->update($data, $pickId);

        if ($pick) {
            return $this->find($pickId);
        }

        return false;
    }
}
