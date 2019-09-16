<?php

namespace Platform\Customer\Commands;

class ShowCustomerByIdCommand
{
    /**
     * @var integer
    */
    public $id;
    
    function __construct($id)
    {
        $this->customerId = $id;
    }
}