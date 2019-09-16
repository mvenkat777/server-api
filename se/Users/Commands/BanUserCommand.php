<?php

namespace Platform\Users\Commands;

class BanUserCommand
{
	public $id;

	function __construct($id)
	{
		$this->id = $id;
	}
}