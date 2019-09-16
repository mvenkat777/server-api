<?php
namespace Platform\DirectMessage\Repositories;

use Platform\DirectMessage\Models\Permission;
use Platform\DirectMessage\Repositories\MessageRepository;
use Carbon\Carbon;

/**
* For processing all the CRUD request related to User Permission
*/
class PermissionRepository
{
	/**
	 * @var permission
	 */
	protected $permission;

	/**
	 * @var messages
	 */
	protected $messages;

	public function __construct(Permission $permission, MessageRepository $messages)
	{
		$this->permission = $permission;
		$this->messages = $messages;
	}

	/**
	 * For fetching list of all the chat done by a user 
	 * @param string userId;
	 */
	public function getChatHistoryByUserId($userId)
	{
		return $this->permission->where('userId', $userId)->get();
	}

	/**
	 * For fetching list of all the chat done by a user 
	 * @param string userId;
	 */
	public function getChatHistoryByUserIdAndChatId($userId, $chatId)
	{
		return $this->permission->where('userId', $userId)->where('chatId', $chatId)->get();
	}

	public function setFavourite($data, $userId)
	{
		if($data->update){
			return $this->permission->where('userId', $userId)->push(['favourites' => $data->messageId]);
		}
		else{
			return $this->permission->where('userId', $userId)->pull(['favourites' => $data->messageId]);
		}
	}

	public function getSharedFiles($userId, $chatId)
	{
		return $this->permission->where('userId', $userId)->where('chatId',  $chatId)->first();
	}

	public function storePermission($data)
	{
		$users = $data->members;
		$structure = [
			'members' => $users,
			'chatId' => $data->chatId,
			'isGroup' => $data->isGroup,
			'favourites' => [],
			'shared' => [],
			'seen' => 0,
			'createdAt' => Carbon::now()->toDateTimeString()
		];
		foreach ($users as $key => $value) {
			$structure['userId'] = $value['id'];

			$this->permission->create($structure);
		}
		return 1;
	}

	public function getMatchedChatId($userId, $isGroup)
	{
		return $this->permission->where('userId', $userId)->where('isGroup', $isGroup)->get();
	}

	public function seen($userId, $chatId, $messageId)
	{
		$isSeen = $this->permission->where('userId', $userId)
								->where('chatId',$chatId)
								 ->push('seenList', [
								 	$messageId
								 ], true);
		if($isSeen){
			return 1;
		} else {
			return 0;
		}
	}

	public function count($userId)
	{
		$unreadCount = 0;
		$history = $this->getChatHistoryByUserId($userId)->toArray();
		if(count($history)){
			foreach ($history as $key => $value) {
				if(isset($value['seenList'])){
					$dm =$this->messages->getChatHistoryByChatId($value['chatId']);
					if(!is_null($dm)){
						$unreadCount = $unreadCount + count(array_diff(array_column($dm->toArray()['messages'], 'messageId'), $value['seenList']));
					}
				}
			}			
		}
		return $unreadCount;
	}
}