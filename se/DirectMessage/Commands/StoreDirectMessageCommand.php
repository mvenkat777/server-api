<?php
namespace Platform\DirectMessage\Commands;

/**
* To store new direct message 
*/
class StoreDirectMessageCommand
{
	/**
	 * @var string chatId
	 */
	public $chatId;

	/**
	 * @var string message
	 */
	public $message;

	/**
	 * @var string type
	 */
	public $type;

	/**
	 * @var bool isFavourite
	 */
	public $isFavourite;

	/**
	 * @var array members
	 */
	public $members;

	/**
	 * @var array owner
	 */
	public $owner;

	function __construct($data, $chatId)
	{
		$this->chatId = $chatId;
		$this->message = $data['message'];
		$this->type = $data['type'];
		$this->isFavourite = false;
		$this->members = isset($data['members'])?$data['members']: [];
		$this->owner = isset($data['owner'])?$data['owner']: [];
	}
}