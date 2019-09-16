<?php

namespace Platform\Tasks\Commands;

class RemoveTagCommand
{
	/**
	 * @var string
	 */
	public $tagId;

	/**
	 * @var string
	 */
	public $taskId;

	/**
	 * @param array $data 
	 * @param string $taskId   
	 */
	function __construct($taskId, $tagId)
	{
		$this->taskId = $taskId;
		$this->tagId = $tagId;
	}
}