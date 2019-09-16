<?php
namespace Platform\Collab\Commands;

/**
* UpdateUserLastSeenStateCommand $chatId
* @return mixed
*/
class UpdateUserLastSeenStateCommand
{
	public $actor;
	function __construct($chatId)
	{
		$this->actor = \Auth::user()->id;
	}
}