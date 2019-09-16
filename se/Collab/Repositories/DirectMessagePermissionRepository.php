<?php
namespace Platform\Collab\Repositories;

use Platform\Collab\Models\OneToOnePermission;

/* This Class is basically defined for all actions related to the permissions of
 * direct messaging
 */
class DirectMessagePermissionRepository {

	/**
	 * @var permission
	 */
	protected $permission;

	public function __construct(OneToOnePermission $permission)
	{
		$this->permission = $permission;
	}

	public function getDetailsByUserId($userId)
	{
		return $this->permission->where('userId', $userId)->first(); 
	}

	public function getConversationIdByUserId($initiator, $participant)
	{
		return $this->permission->where('userId', $initiator)
								->where('chat.'.$participant.'.participant', $participant)
								->first();
	}

	public function getConversationIdByUserIdOfGroup($initiator, $participant)
	{
		return $this->permission->where('userId', $initiator)
								->first()
								->group[$participant];
	}

	public function getGroupConversation($initiator, $conversationId)
	{
		return $this->permission->where('userId', $initiator)
								->where('group.'.$conversationId.'.chatId', $conversationId)
								->first();
	}

	public function registerNewConversation($data)
	{
		$user = $this->getDetailsByUserId($data['userId']);
		if($user){
			$userArray = [];
			$userArray = array_merge($user['chat'], $data['chat']);
			$isUpdated = $this->updateWithNewConversation($data['userId'], 'chat', $userArray);
			if($isUpdated){
				return $this->getDetailsByUserId($data['userId']);
			}
			return 0;
		}
		$data['group'] = [];
		return $this->permission->create($data);
	}

	public function registerNewGroupConversation($data)
	{
		$user = $this->getDetailsByUserId($data['userId']);
		if($user){
			$userArray = [];
			$userArray = array_merge($user['group'], $data['group']);
			$isUpdated = $this->updateWithNewConversation($data['userId'], 'group', $userArray);
			if($isUpdated){
				return $this->getDetailsByUserId($data['userId']);
			}
			return 0;
		}
		$data['chat'] = [];
		return $this->permission->create($data);
	}

	public function updateWithNewConversation($id, $column, $data)
	{
		return $this->permission->where('userId', $id)->update([$column => $data]);
	}

	public function updateFavourites($actorId, $chatId, $messageId, $participant)
	{
		$isUpdated = $this->permission->where('userId', $actorId)
								->push(['chat.'.$participant.'.favourites' => $messageId]);
		if($isUpdated) {
			return $this->getConversationIdByUserId($actorId, $participant);
		} else{
			return 0;
		}
	}

	public function updateFavouritesForGroup($actorId, $chatId, $messageId, $participant)
	{
		$isUpdated = $this->permission->where('userId', $actorId)
								->push(['group.'.$participant.'.favourites' => $messageId]);
		if($isUpdated) {
			return $isUpdated;
			return $this->getConversationIdByUserIdOfGroup($actorId, $participant);
		} else{
			return 0;
		}
	}

	public function removeFavourites($actorId, $chatId, $messageId, $participant)
	{
		$isUpdated = $this->permission->where('userId', $actorId)
								->pull(['chat.'.$participant.'.favourites' => $messageId]);
		if($isUpdated) {
			return $this->getConversationIdByUserId($actorId, $participant);
		} else{
			return 0;
		}
	}

	public function getGroupConversationIdByUserId($initiator, $participant = NULL)
	{
		return $this->permission->where('userId', $initiator)
								->first();
	}
}