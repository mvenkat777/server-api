<?php

namespace Platform\Customer\Commands;

class DeleteCustomerBrandCommand
{

    /**
     * @var integer
    */
    public $customerId;

    /**
     * @var integer
    */
    public $brandId;
    
    function __construct($customerId, $brandId)
    {
        $this->customerId = $customerId;
		$this->brandId = $brandId;
    }


}