<?php

namespace Platform\Tasks\Commands;

class DeleteTaskCommand
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