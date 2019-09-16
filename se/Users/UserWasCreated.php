<?php
namespace Platform\Users;

use Platform\Users\Commands\CreateUserCommand;

class UserWasCreated
{
	public $command;
	public $confirmationCode;

	function __construct(CreateUserCommand $command , $confirmationCode)
	{
		$this->command = $command;
		$this->confirmationCode = $confirmationCode;
	}
}