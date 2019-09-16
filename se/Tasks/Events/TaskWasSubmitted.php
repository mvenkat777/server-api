<?php
namespace Platform\Tasks\Events;

class TaskWasSubmitted
{
	public $task;
	
	function __construct($task)
	{
		$this->task = $task;
	}
}