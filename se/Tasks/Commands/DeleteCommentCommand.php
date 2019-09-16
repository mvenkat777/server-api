<?php

namespace Platform\Tasks\Commands;

class DeleteCommentCommand 
{
	/**
	 * @var string
	 */
	public $taskId;

	/**
	 * @var string
	 */
	public $commentId;

	/**
	 * @param string $taskId
	 * @param string $commentId
	 */
	public function __construct($taskId, $commentId){
		$this->taskId = $taskId;
		$this->commentId = $commentId;
	}

}