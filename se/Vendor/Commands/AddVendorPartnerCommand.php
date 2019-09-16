<?php

namespace Platform\Vendor\Commands;



class AddVendorPartnerCommand
{
	/**
	 * @var string
	 */
    public $vendorId;

    /**
	 * @var array
	 */
	public $partners;
	
	function __construct($data, $vendorId)
    {	
        $this->vendorId = $vendorId;
        $this->partners = is_null($data['partners'])? NULL : $data['partners'];
    } 
}