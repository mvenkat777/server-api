<?php

namespace Platform\Authentication\Commands;

class ResetPasswordCommand 
{
	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string
	 */
    public $resetPin;

    /**
	 * @var string
	 */
    public $password;

    function __construct($data)
    {
        $this->email = $data['email'];
        $this->resetPin = $data['token'];
        $this->password = $data['password'];
	}

}