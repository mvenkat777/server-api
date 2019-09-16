<?php

namespace Platform\Customer\Commands;

class DeleteCustomerContactCommand
{

    /**
     * @var integer
    */
    public $customerId;

    /**
     * @var integer
    */
    public $contactId;
    
    function __construct($customerId, $contactId)
    {
        $this->customerId = $customerId;
		$this->contactId = $contactId;
    }


}