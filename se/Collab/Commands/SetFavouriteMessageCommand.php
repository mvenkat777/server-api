<?php
namespace Platform\Collab\Commands;

/**
* Set Or Remove Message as/From being Favourite
*/
class SetFavouriteMessageCommand
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
	 * @var bool flag
	 */
	public $flag;

	function __construct($data, $flag)
	{
		$this->chatId = $data['chatId'];
		$this->messageId = $data['messageId'];
		$this->flag = $flag;
	}
}