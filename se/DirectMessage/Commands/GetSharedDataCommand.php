<?php
namespace Platform\DirectMessage\Commands;

/**
* To get all the shared files by a user
*/
class GetSharedDataCommand
{
	/**
	 * @var string userId
	 */
	public $userId;

	/**
	 * @var string chatId
	 */
	public $chatId;

	function __construct($data)
	{
		$this->userId = $data['userId'];
		$this->chatId = $data['chatId'];
	}
}