<?php

namespace Platform\Tasks\Commands;

use Platform\App\Helpers\Helpers;

class AddAttachmentCommand
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

	/**
	 * @var string
	 */
	public $creator;

	/**
	 * @param array $data  
	 * @param string $taskId
	 * @param string $type  
	 */
	function __construct($data, $taskId)
	{
		$this->data = $data['data'];
		$this->taskId = $taskId;
		$this->type = Helpers::isSetAndIsNotEmpty($data , 'type')
								? $data['type']
								: 'file';
		$this->creator = \Auth::user()->id;
	}
}