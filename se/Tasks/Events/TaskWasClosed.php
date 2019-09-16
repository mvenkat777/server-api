<?php
namespace Platform\Tasks\Events;

class TaskWasClosed
{
	public $task;
	
	function __construct($task)
	{
		$this->task = $task;
	}
}