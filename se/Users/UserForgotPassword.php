<?php
namespace Platform\Users;

use Platform\Users\Commands\SetTemporaryPasswordCommand;

class UserForgotPassword
{
	public $command;
	public $token;

	function __construct(SetTemporaryPasswordCommand $command , $token)
	{
		$this->command = $command;
		$this->token = $token;
	}
}