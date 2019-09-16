<?php
namespace Platform\Collab\Commands;

/**
* 
*/
class GetMessageByChatIdCommand
{
	public $chatId;
	
	function __construct($data)
	{
		$this->chatId = $data['chatId'];
	}
}