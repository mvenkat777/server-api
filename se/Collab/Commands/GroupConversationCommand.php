<?php
namespace Platform\Collab\Commands;

/**
* GroupConversationCommand $command
* @return mixed
*/
class GroupConversationCommand
{
	protected $members;
	
	function __construct($data)
	{
		$this->members = $data['members'];
	}
}