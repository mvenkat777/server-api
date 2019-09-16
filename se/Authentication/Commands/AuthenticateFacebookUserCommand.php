<?php

namespace Platform\Authentication\Commands;

class AuthenticateFacebookUserCommand 
{
	/**
	 * Facebook Token
	 * @var string
	 */
	public $token;

	/**
	 * @param string $token
	 */
	public function __construct($token){
		$this->token = $token;
	}

}