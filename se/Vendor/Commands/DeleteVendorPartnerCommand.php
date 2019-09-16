<?php

namespace Platform\Vendor\Commands;

class DeleteVendorPartnerCommand
{
    /**
     * @var integer
    */
    public $vendorId;

    /**
     * @var integer
    */
    public $partnerId;
    
    function __construct($vendorId, $partnerId)
    {
        $this->vendorId = $vendorId;
		$this->partnerId = $partnerId;
    }
}