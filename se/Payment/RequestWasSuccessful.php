<?php
namespace Platform\Payment;


class RequestWasSuccessful
{
	public $command;
	

	function __construct($command)
	{
		$this->command = $command;
	}
}