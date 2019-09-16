<?php
namespace Platform\Tasks\Events;

class TaskWasCompleted
{
	public $task;
	
	function __construct($task)
	{
		$this->task = $task;
	}
}