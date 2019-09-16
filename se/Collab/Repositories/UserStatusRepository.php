<?php
namespace Platform\Collab\Repositories;

use Carbon\Carbon;
use Platform\Collab\Models\UserStatus;
use Platform\Collab\Repositories\Repository;

/**
 * To perform all the CRUD request related to user status
 *
 */
class UserStatusRepository extends Repository {

	/**
	 * @var userState
	 */
	protected $UserStatus;

	public function __construct(UserStatus $UserStatus)
	{
		$this->UserStatus = $UserStatus;
	}

	/**
	 * To store new UserStatus
	 * @return mixed
	 */
	public function store($userId,$lastUpdatedDateTime)
	{
		$data = [
		'userId' => $userId,
		'lastUpdatedDateTime' => $lastUpdatedDateTime
		];
		return $this->UserStatus->create($data);
	}

	/**
	 * To update existing UserStatus
	 * @return mixed
	 */
	public function update($userId,$lastUpdatedDateTime){
		$isExists = $this->UserStatus->where('userId', $userId)->first();
		if($isExists == null){
			$this->store($userId,$lastUpdatedDateTime);
		}else{
			$this->UserStatus->where('userId', $userId)->update(['lastUpdatedDateTime' => $lastUpdatedDateTime]);
		}
		return $this->getAllUser();
	}

	/**
	 * To get existing UpdatedDateTime
	 * @return mixed
	 */
	public function getUpdatedDateTime($userId){

		$userRow = $this->UserStatus->where('userId', $userId)->first();
		
		return $userRow;
	}

	public function getAllUser()
	{
		$user = UserStatus::all();
		foreach ($user as $key => $value) {
			$now = Carbon::now();
			$lastUpdated = Carbon::parse($value['lastUpdatedDateTime']);
			$diff = $lastUpdated->diffInSeconds($now);
			if($diff >= 90)
			{
				$user[$key]['isOnline'] = false; 
			} else {
				$user[$key]['isOnline'] = true;
			}
		}	
		return $user;
	}
	
}