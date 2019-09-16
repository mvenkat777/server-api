<?php
namespace Platform\Collab\Commands;

/**
* Assign a user as manager
*/
class CollabManagerCommand
{
	/**
	 * @var number collabId
	 */
	public $collabId;

	/**
	 * @var number userId
	 */
	public $userId;

	/**
	 * @var bool set
	 */
	public $set;

	function __construct($collabId, $userId, $set)
	{
		$this->collabId = $collabId;
		$this->userId = $userId;
		$this->set = $set;
	}
}