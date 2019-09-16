<?php
namespace Platform\Collab\Commands;

/**
* To update the direct message content
*/
class UpdateCardCommand
{
	/**
	 * @var number collabId
	 */
	public $collabId;

	/**
	 * @var number cardId
	 */
	public $cardId;

	/**
	 * @var string message
	 */
	public $message;

	function __construct($data)
	{
		$this->collabId = $data['collabId'];
		$this->cardId = $data['cardId'];
		$this->message = $data['message'];
	}
}