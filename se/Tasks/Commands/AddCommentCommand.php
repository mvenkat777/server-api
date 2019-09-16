<?php

namespace Platform\Tasks\Commands;

class AddCommentCommand 
{
	/**
	 * @var string/enum
	 */
	public $type;

	/**
	 * @var text/file
	 */
	public $data;

	/**
	 * @var string
	 */
	public $taskId;

	/**
	 * @param string $taskId
	 * @param array $data  
	 */
	public function __construct($taskId, $data){
		$this->taskId = $taskId;
		$this->data = $data['data'];
		$this->type = $data['type'];
	}

}