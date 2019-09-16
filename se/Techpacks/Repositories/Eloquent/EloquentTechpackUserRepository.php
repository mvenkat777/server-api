<?php

namespace Platform\Techpacks\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Techpacks\Repositories\Contracts\TechpackUserRepository;

class EloquentTechpackUserRepository extends Repository implements TechpackUserRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return 'App\TechpackUser';
    }

    public function share($techpackId, $user)
    {
        $share = [
            'techpack_id' => $techpackId,
            'user_id' => $user->id,
            'permission' => 'can_read',
        ];
        if (!$this->findByTechpackAndUser($techpackId, $user->id)) {
            return $this->model->create($share);
        }

        return true;
    }

    public function findByTechpackAndUser($techpackId, $userId)
    {
        return $this->model->where('techpack_id', $techpackId)
                    ->where('user_id', $userId)
                    ->first();
    }

    public function searchShare($command)
    {
        $user = \Auth::user();

        return ($user->techpacks()->where('techpack_user.permission', '=', 'can_read')
                                  ->orWhere('techpack_user.permission', '=', 'can_edit')
                                  ->paginate($command->item));
    }

    public function addOwner($techpackId, $userId)
    {
        $techpackUser = [
            'user_id' => $userId,
            'techpack_id' => $techpackId,
            'permission' => 'owner',
        ];

        return $this->model->create($techpackUser);
    }
}
