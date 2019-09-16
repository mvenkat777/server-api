<?php

namespace Platform\Tasks\Commands;

class CloseTaskCommand
{
	/**
	 * Task id
	 * @var string
	 */
	public $taskId;

	/**
	 * @var text
	 */
	public $note;

	/**
	 * @param array $data
	 * @param string $taskId  
	 */
	function __construct($data, $taskId)
	{
		$this->taskId = $taskId;
		$this->note = isset($data['note']) ? $data['note'] : NULL;
	}
}