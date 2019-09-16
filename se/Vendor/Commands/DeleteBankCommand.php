<?php

namespace Platform\Vendor\Commands;

class DeleteBankCommand
{

    /**
     * @var integer
    */
    public $vendorId;

    /**
     * @var integer
    */
    public $bankId;
    
    function __construct($vendorId, $bankId)
    {
        $this->vendorId = $vendorId;
		$this->bankId = $bankId;
    }


}