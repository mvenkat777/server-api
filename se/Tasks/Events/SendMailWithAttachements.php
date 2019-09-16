<?php
namespace Platform\Tasks\Events;

class SendMailWithAttachements
{
	public $task;
	
	function __construct($task)
	{
		$this->task = $task;
	}
}