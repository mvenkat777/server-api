<?php

namespace Platform\Tasks\Commands;

class CreateTagCommand
{
	/**
	 * @var string
	 */
	public $title;

	/**
	 * @param string $title
	 */
	function __construct($title)
	{
		$this->title = $title;
	}
}