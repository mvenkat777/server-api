<?php
namespace Platform\Authentication\Events;

use Platform\Authentication\Commands\SendResetPasswordLinkCommand;

class UserForgotPassword
{
	public $command;
	public $token;

	function __construct(SendResetPasswordLinkCommand $command , $token)
	{
		$this->command = $command;
		$this->token = $token;
	}
}