<?php
namespace Platform\Collab\Commands;

/**
* To invite users in a collab
*/
class InviteUserCommand
{
	public $collabId;

	public $cardId;

	function __construct($collabId, $cardId)
	{
		$this->collabId = $collabId;
		$this->cardId = $cardId;
	}
}