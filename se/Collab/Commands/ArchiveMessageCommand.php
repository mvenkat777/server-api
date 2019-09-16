<?php
namespace Platform\Collab\Commands;

/**
* To archive a direct message
*/
class ArchiveMessageCommand
{
	/**
	 * @var number chatId
	 */
	public $chatId;

	/**
	 * @var number messageId
	 */
	public $messageId;

	function __construct($data)
	{
		$this->chatId = $data['chatId'];
		$this->messageId = $data['messageId'];
	}
}