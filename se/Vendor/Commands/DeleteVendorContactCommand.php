<?php

namespace Platform\Vendor\Commands;

class DeleteVendorContactCommand
{

    /**
     * @var integer
    */
    public $vendorId;

    /**
     * @var integer
    */
    public $contactId;
    
    function __construct($vendorId, $contactId)
    {
        $this->vendorId = $vendorId;
		$this->contactId = $contactId;
    }


}