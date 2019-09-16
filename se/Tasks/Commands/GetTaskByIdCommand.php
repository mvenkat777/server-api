<?php

namespace Platform\Tasks\Commands;

class GetTaskByIdCommand
{
	/**
	 * @var string
	 */
	public $id;

	/**
	 * @var string
	 */
	function __construct($id)
	{
		$this->id = $id;
	}
}