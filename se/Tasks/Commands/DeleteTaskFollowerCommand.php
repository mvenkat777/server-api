<?php

namespace Platform\Tasks\Commands;

class DeleteTaskFollowerCommand 
{
	/**
	 * @var string
	 */
	public $taskId;

	/**
	 * @var string
	 */
	public $followerId;

	public function __construct($taskId, $followerId){
		$this->taskId = $taskId;
		$this->followerId = $followerId;
	}

}