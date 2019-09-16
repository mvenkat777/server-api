<?php

namespace Platform\Tasks\Commands;

class ChangeTaskPriorityCommand
{
	/**
	 * @var string
	 */
	public $taskId;
	
	/**
	 * @var string enum
	 */
	public $priority;

	/**
	 * @param array $data   
	 * @param string $taskId 
	 */
	function __construct($data, $taskId)
	{
		$this->priority = $data['priority'];
		$this->taskId = $taskId;
	}
}