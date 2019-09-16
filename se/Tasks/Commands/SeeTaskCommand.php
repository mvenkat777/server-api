<?php

namespace Platform\Tasks\Commands;

class SeeTaskCommand
{
	/**
	 * @var string
	 */
	public $taskId;

	/**
	 * @var string
	 */
	function __construct($taskId)
	{
		$this->taskId = $taskId;
	}
}