<?php
namespace Platform\Tasks\Commands;

class UpdateAttachmentCommand 
{
	/**
	 * @var array
	 */
	public $data;

	/**
	 * @var string
	 */
	public $taskId;

	/**
	 * @var string
	 */
	public $type;

	public $attachment;

	/**
	 * @var string
	 */
	public $creator;

	/**
	 * @param array $data   
	 * @param string $taskId 
	 * @param string $type   
	 */
	function __construct($data, $taskId, $type, $attachment)
	{
		$this->data = $data;
		$this->taskId = $taskId;
		$this->type = $type;
		$this->attachment = $attachment;
		$this->creator = \Auth::user()->id;
	}
	
}