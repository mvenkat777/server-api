<?php
namespace Platform\Payment;

class RequestWasFailed
{
	public $command;

	function __construct($command)
	{
		$this->command = $command;
	}
}