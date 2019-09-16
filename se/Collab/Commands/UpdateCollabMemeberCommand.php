<?php
namespace Platform\Collab\Commands;

/**
* UpdateCollabMemeberCommand $command
* To perform add or delete member from a collab
*/
class UpdateCollabMemeberCommand
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

	function __construct($data, $set)
	{
		$this->collabId = $data['collabId'];
		$this->userId = $data['members'];
		$this->set = $set;
	}
}