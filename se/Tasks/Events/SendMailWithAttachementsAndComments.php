<?php
namespace Platform\Tasks\Events;

class SendMailWithAttachementsAndComments
{
	public $task;
	
	function __construct($task)
	{
		$this->task = $task;
	}
}