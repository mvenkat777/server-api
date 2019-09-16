<?php

namespace Platform\Tasks\Commands;

class SendMailForTaskOwnerCommand
{
	/**
	 * @var string
	 */
	public $id;

	/**
	 * @var string
	 */
	public $data;

	/**
	 * @param string $taskId 
	 * @param array $data   
	 */
	function __construct($taskId, $data)
	{
		$this->id = $taskId;
		$this->type = isset($data['type'])? strtolower($data['type']):NULL;
	}
}