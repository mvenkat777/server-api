<?php

namespace Platform\Picks\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Picks\Repositories\Contracts\PickCommentRepository;
use App\PickComment;

class EloquentPickCommentRepository extends Repository implements PickCommentRepository 
{

	public function model(){
		return 'Platform\Picks\Models\PickComment';
	}

    public function addComment($pickId, $data)
    {
        $data = [
            'id' => $this->generateUUID(),
            'pick_id' => $pickId,
            'comment' => $data['comment'],
            'commentator_id' => \Auth::user()->id,
        ];

        return $this->create($data);
    }

}
