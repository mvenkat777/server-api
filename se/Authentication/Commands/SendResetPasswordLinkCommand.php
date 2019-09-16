<?php

namespace Platform\Authentication\Commands;

class SendResetPasswordLinkCommand 
{
	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string
	 */
	public $app;

	public function __construct($data){
		$this->email = isset($data['email']) ? $data['email'] : NULL;
		$this->app = isset($data['app']) ? "http://".$data['app'] : $_SERVER['HTTP_ORIGIN'];
	}

}