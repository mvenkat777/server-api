<?php

namespace Platform\Tasks\Commands;

class StartTaskCommand 
{
	/**
	 * @var string UUID
	 */
	public $taskId;

	public function __construct($data, $taskId){
		$this->taskId = $taskId;
        $this->seeTask = isset($data['seeTask']) ? $data['seeTask'] : false;
	}

}
