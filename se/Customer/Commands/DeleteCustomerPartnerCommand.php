<?php

namespace Platform\Customer\Commands;

class DeleteCustomerPartnerCommand
{
    /**
     * @var integer
    */
    public $customerId;

    /**
     * @var integer
    */
    public $partnerId;
    
    function __construct($customerId, $partnerId)
    {
        $this->customerId = $customerId;
		$this->partnerId = $partnerId;
    }
}