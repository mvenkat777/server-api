<?php

namespace Platform\Tasks\Commands;

class DeleteAttachmentCommand
{
	/**
	 * @var string
	 */
	public $attachmentId;

	/**
	 * @var string
	 */
	public $taskId;

	/**
	 * @param string $taskId
	 * @param string $attachmentId  
	 */
	function __construct($taskId, $attachmentId)
	{
		$this->taskId = $taskId;
		$this->attachmentId = $attachmentId;
	}
}