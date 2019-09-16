<?php

namespace Platform\Tasks\Commands;

use Platform\App\Helpers\Helpers;

class ReassignTaskCommand 
{
	/**
	 * @var string
	 */
	public $taskId;

	/**
	 * @var date
	 */
	public $dueDate;

	/**
	 * @var email
	 */
	public $assignee;

	public function __construct($taskId, $data){
		$this->taskId = $taskId;
		$this->dueDate = Helpers::isSetAndIsNotEmpty($data, 'dueDate')
							? $data['dueDate']
							: NULL;
		$this->assignee = $data['assignee'];
	}

}