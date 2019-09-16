<?php
namespace Platform\Payment;



class PaymentLinkWasCreated
{
	public $command;

	function __construct($command )
	{
		$this->command = $command;
	}
}