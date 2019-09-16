<?php

namespace Platform\Tasks\Commands;

class CompleteTaskCommand
{
	/**
	 * @var text
	 */
	public $note;

	/**
	 * Task id
	 * @var string
	 */
	public $id;

	/**
	 * @param array $data
	 * @param string $id  
	 */
	function __construct($data, $id)
	{
		$this->note = isset($data['note'])?$data['note']:'';
		$this->id = $id;
	}
}