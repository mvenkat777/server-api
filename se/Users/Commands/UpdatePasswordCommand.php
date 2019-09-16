<?php

namespace Platform\Users\Commands;

class UpdatePasswordCommand 
{
	/**
	 * @var string
	 */
	public $password;

	public function __construct($data){
		$this->password = $data['password'];
	}

}