<?php

namespace Platform\Tasks\Commands;

class AddTagCommand
{
	/**
	 * @var string
	 */
	public $tag;

	/**
	 * @var string
	 */
	public $taskId;

	/**
	 * @param array $data
	 * @param string $taskId  
	 */
	function __construct($data, $taskId)
	{
		$this->tag = $data['tag'];
		$this->taskId = $taskId;
	}
}