<?php

namespace Platform\Boards\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Boards\Repositories\Contracts\BoardRepository;

use Platform\App\Helpers\Helpers;

class EloquentBoardRepository extends Repository implements BoardRepository 
{
    public function model() 
    {
		return 'Platform\Boards\Models\Board';
	}

    /**
     * Creates a new board
     *
     * @param array $data
     */
    public function createBoard($data)
    {
        $data = [
            'id' => $this->generateUUID(),
            'name' => $data['name'],
            'cover' => json_encode($data['cover']),
            'description' => $data['description'],
            'sales_lead_id' => $data['salesLeadId'],
            'author_id' => Helpers::getAuthUserId(),
        ];
        
        return $this->model->create($data);  
    }

    /**
     * Get a board with its relations
     *
     * @param mixed $boardId
     */
    public function getByIdWithRelations($boardId)
    {
        return $this->model->with(['author', 'salesLead'])
                           ->find($boardId);
    }
}
