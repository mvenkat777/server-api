<?php

namespace Platform\Vendor\Commands;

class ShowVendorByIdCommand
{
    /**
     * @var integer
    */
    public $vendorId;
    
    function __construct($id)
    {
        $this->vendorId = $id;
    }
}