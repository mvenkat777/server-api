<?php

namespace Platform\Authentication\Commands;

class LogOutUserCommand 
{
	/**
	 * access-token
	 * @var string
	 */
	public $token;

	/**
	 * @param string
	 */
	public function __construct($token){
		$this->token = $token;
	}

}