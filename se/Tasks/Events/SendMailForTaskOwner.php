<?php
namespace Platform\Tasks\Events;

class SendMailForTaskOwner
{
	public $task;
	
	function __construct($task)
	{
		$this->task = $task;
	}
}