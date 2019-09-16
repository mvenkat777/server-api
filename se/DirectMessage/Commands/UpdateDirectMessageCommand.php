<?php
namespace Platform\DirectMessage\Commands;

/**
* To update the direct message
*/
class UpdateDirectMessageCommand
{
	/**
	 * @var string chatId
	 */
	public $chatId;

	/**
	 * @var string messageId
	 */
	public $messageId;

	/**
	 * @var string type
	 */
	public $type;

	/**
	 * @var string key
	 */
	public $key;

	/**
	 * @var string update
	 */
	public $update;

	function __construct($data)
	{
		$this->chatId = $data['chatId'];
		$this->messageId = $data['messageId'];
		$this->type = isset($data['type'])?$data['type']:NULL;
		$this->key = $data['data']['key'];
		$this->update = $data['data']['update'];
	}
}