<?php

namespace Platform\Users\Commands;

class UnBanUserCommand
{
	public $id;

	function __construct($id)
	{
		$this->id = $id;
	}
}