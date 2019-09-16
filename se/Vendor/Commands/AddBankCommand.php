<?php

namespace Platform\Vendor\Commands;



class AddBankCommand
{
	/**
	 * @var string
	 */
    public $vendorId;

    /**
	 * @var array
	 */
	public $banks;
	
	function __construct($data, $vendorId)
    {	
        $this->vendorId = $vendorId;
        $this->banks = is_null($data['banks'])? NULL : $data['banks'];
    } 
}