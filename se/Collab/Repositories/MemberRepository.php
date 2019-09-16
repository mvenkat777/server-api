<?php

namespace Platform\Collab\Repositories;

use Platform\Collab\Repositories\Repository;
use Platform\Collab\Models\Member;

/**
* This class is defined to do all CRUD operations related to member
*/
class MemberRepository extends Repository
{
	/**
	 * @var member
	 */
	protected $member;

	public function __construct(Member $member)
	{
		$this->member = $member;
	}

	/**
	 * To store new member [Public / Private]
	 * @return mixed
	 */
	public function store($userId){
		$data = [
			'userId' => $userId,
			'collab' => []
		];
		return $this->member->create($data);
	}

	/**
	 * To update existing member with new [Public / Private]
	 * @return mixed
	 */
	public function update($userId, $details){
		return $this->member->where('userId', $userId)->push('collab', $details);
	}

	public function add($details, $userId){
		$check = $this->member->where('userId', $userId)->first();
		if($check){
			return $this->update($userId, $details);
		} else {
			$isStored = $this->store($userId);
			if($isStored){
				return $this->update($userId, $details);
			}
		}
	}

	public function remove($collabId, $userId)
	{
		return $this->member->where('userId', $userId)
							 ->where('collab.collabId', $collabId)
							 ->pull('collab',[
							 	'collabId' => $collabId
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

	public function manipulate($member)
	{
		foreach ($member->members as $key => $value) {
			$isExists = $this->member->where('userId', $value)->first();
			if($isExists){
				$this->update($value, $member->details[$key]);
			} else {
				$this->store($value);
				$this->update($value, $member->details[$key]);
			}
		}
		return 1;
	}

	public function updateFavourites($actorId, $collabId, $cardId)
	{
		$isFavourite = $this->member->where('userId', $actorId)
							 ->where('collab.collabId', $collabId)
							 ->push([
							 	'collab.$.favourites' => $cardId
							 ]);
		return $isFavourite;
	}

	public function removeFavourites($actorId, $collabId, $cardId)
	{
		$isRemoved = $this->member->where('userId', $actorId)
							 ->where('collab.collabId', $collabId)
							 ->pull([
							 	'collab.$.favourites' => $cardId
							 ]);
		return $isRemoved;
	}
}