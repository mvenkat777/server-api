<?php
namespace Platform\Collab\Commands;

/**
* To share a message with another user
*/
class ShareMessageCommand
{
	/**
	 * Chat Id
	 * @var number chatId
	 */
	public $chatId;

	/**
	 * Message Id
	 * @var number messageId
	 */
	public $messageId;

	/**
	 * Share Id
	 * @var number shareId
	 */
	public $shareId;

	/**
	 * Person with whom shared chat id
	 * @var number convIdOfSharedUser
	 */
	public $convIdOfSharedUser;

	function __construct($data)
	{
		$this->chatId = $data['chatId'];
		$this->messageId = $data['messageId'];
		$this->shareId = $data['shareWith'];
		$this->convIdOfSharedUser = $data['convIdOfSharedUser'];
	}
}