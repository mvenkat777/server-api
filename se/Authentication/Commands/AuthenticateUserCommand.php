<?php

namespace Platform\Authentication\Commands;

class AuthenticateUserCommand 
{
	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string
	 */
	public $password;

	/**
	 * @param array $data
	 */
	public function __construct($data)
	{
		$this->email = $data->email;
		$this->password = $data->password;
	}
}