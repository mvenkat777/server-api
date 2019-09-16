<?php
namespace Platform\Collab\Commands;

/**
* To get a message by its ID
* @param data
*/
class GetMessageByIdCommand
{
	public $chatId;
	public $messageId;

	function __construct($data)
	{
		$this->chatId = $data['chatId'];
		$this->messageId = $data['messageId'];
	}
}