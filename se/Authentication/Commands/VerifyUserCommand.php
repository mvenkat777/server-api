<?php

namespace Platform\Authentication\Commands;

class VerifyUserCommand
{
	/**
	 * @var string
	 */
	public $code;

	/**
	 * @var string
	 */
	public $password;

	/**
	 * @param string $code
	 */
	public function __construct($code, $password = null){
		$this->code = $code;
		$this->password = $password;
	}

}