<?php

namespace Platform\Tasks\Commands;

class AddTaskFollowerCommand 
{
	/**
	 * @var string
	 */
	public $taskId;

	/**
	 * @var array
	 */
	public $followers;

	public function __construct($taskId, $data){
		$this->taskId = $taskId;
		$this->followers = $data['followers'];
	}

}