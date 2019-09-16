<?php
namespace Platform\DirectMessage\Commands;

/**
* To fetch all the direct message by userId or Message By Chat Id
*/
class GetDirectMessageCommand
{
	/**
	 * @var string userId
	 */
	public $userId;

	/**
	 * @var string id
	 */
	public $id;

	function __construct($data)
	{
		$this->userId = $data['userId'];
		$this->id = $data['id'];
	}
}