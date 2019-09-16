<?php

namespace Platform\Customer\Commands;

class DeleteCustomerAddressCommand
{

    /**
     * @var integer
    */
    public $customerId;

    /**
     * @var integer
    */
    public $addressId;
    
    function __construct($customerId, $addressId)
    {
        $this->customerId = $customerId;
		$this->addressId = $addressId;
    }


}