<?php

namespace Platform\Collab\Repositories;

use Platform\Collab\Repositories\Repository;
use Platform\Collab\Models\Collab;

/**
* This class is when there will be any CRUD operation related to collab group activity
*/
class CollabRepository extends Repository
{
	/**
	 * @var collab
	 */
	protected $collab;

	public function __construct(Collab $collab)
	{
		$this->collab = $collab;
	}

	/**
	 * To store new collab [Public / Private]
	 * @return mixed
	 */
	public function store($data){
		return $this->collab->create($data);
	}

	public function getByCollabId($collabId)
	{
		return $this->collab->where('_id', $collabId)->first();
	}

	public function getAllPublicCollab()
	{
		return $this->collab->where('isPublic', true)->get();
	}
}