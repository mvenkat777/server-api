<?php

namespace Platform\Customer\Commands;

class DeleteCustomerCommand{

	/**
	 * @var string
	 */
    public $id;

    function __construct($id)
    {
        $this->customerId = $id;
    }
}