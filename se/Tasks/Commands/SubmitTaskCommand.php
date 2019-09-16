<?php

namespace Platform\Tasks\Commands;

class SubmitTaskCommand
{
	/**
	 * @var string
	 */
	public $id;

	/**
	 * @param array $data
	 * @param string $id  
	 */
	function __construct($data, $id)
	{
		$this->id = $id;
        $this->seeTask = isset($data['seeTask']) ? $data['seeTask'] : false;
	}
}
