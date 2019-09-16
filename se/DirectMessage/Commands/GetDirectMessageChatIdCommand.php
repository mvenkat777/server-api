<?php
namespace Platform\DirectMessage\Commands;

/**
* To generate new chatId if not exists or fetching the existing one
*/
class GetDirectMessageChatIdCommand
{
	/**
	 * @var array members
	 */
	public $members;

	/**
	 * @var bool isGroup
	 */
	public $isGroup;

	function __construct($data)
	{
		$this->members = $data['members'];
		$this->isGroup = (count($data['members']) > 1) ? true : false; 
	}
}