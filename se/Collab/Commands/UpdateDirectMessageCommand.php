<?php
namespace Platform\Collab\Commands;

/**
* To update the direct message content
*/
class UpdateDirectMessageCommand
{
	/**
	 * @var number chatId
	 */
	public $chatId;

	/**
	 * @var number messageId
	 */
	public $messageId;

	/**
	 * @var string message
	 */
	public $message;

	function __construct($data)
	{
		$this->chatId = $data['chatId'];
		$this->messageId = $data['messageId'];
		$this->message = $data['message'];
	}
}