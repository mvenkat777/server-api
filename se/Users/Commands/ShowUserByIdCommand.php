<?php

namespace Platform\Users\Commands;

class ShowUserByIdCommand
{
	public $id;

	public $token;

	function __construct($id, $token)
	{
		$this->userId = $id;
		$this->token = $token;
	}
}