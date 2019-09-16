<?php

namespace Platform\Authentication\Commands;

class ValidateTokenCommand 
{
	public $token;

	function __construct($token) {
		$this->token = $token;
	}

}