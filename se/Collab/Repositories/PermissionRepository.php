<?php

namespace Platform\Collab\Repositories;

use Platform\Collab\Repositories\Repository;
use Platform\Collab\Models\Permission;

/**
* This class is defined to do save all the users of a collab
*/
class PermissionRepository extends Repository
{
	/**
	 * @var permission
	 */
	protected $permission;

	public function __construct(Permission $permission)
	{
		$this->permission = $permission;
	}

	/**
	 * To store new member [Public / Private]
	 * @return mixed
	 */
	public function store($data){
		$data = [
			'title' => $data->title,
			'id' => $data->id,
			'isPublic' => $data->isPublic,
			'members' => []
		];
		return $this->permission->create($data);
	}

	/**
	 * To update existing member with new [Public / Private]
	 * @return mixed
	 */
	public function update($collabId, $details){
		return $this->permission->where('id', $collabId)->push('members', $details);
	}

	/**
	 * To update existing member with new [Public / Private]
	 * @return mixed
	 */
	public function remove($collabId, $details){
		return $this->permission->where('id', $collabId)
								->where('members.id',$details['id'])
								 ->pull('members', [
								 	'id' => $details['id']
								 ]);
	}

	/**
	 * To get all members [Public / Private] by user [as owner or participant]
	 * @return mixed
	 */
	public function getAllByUser($userId)
	{
		return $this->member->where('userId', $userId)->first();
	}

	public function manipulate($data)
	{		
		$isExists = $this->permission->where('id', $data->id)->first();
		if($isExists){
			$this->update($data->id, $data->users);
		} else {
			$this->store($data);
			$this->update($data->id, $data->user);
		}
		return 1;
	}

	public function getUserIdByCollab($collabId)
	{
		return $this->permission->where('id', $collabId)->first();
	}

	public function assignManager($collabId, $userId)
	{
		return $this->permission->where('id', $collabId)
								->where('members.id', $userId)
								->update([
							 	'members.$.isManager' => true
							 ]);
	}

	public function removeManager($collabId, $userId)
	{
		return $this->permission->where('id', $collabId)
								->where('members.id', $userId)
								->update([
							 	'members.$.isManager' => false
							 ]);
	}
}