<?php
namespace Platform\Tasks\Events;

class TaskWasCreated
{
	public $task;
	
	function __construct($task)
	{
		$this->task = $task;
	}
}