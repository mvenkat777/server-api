<?php

namespace Platform\NamingEngine\Commands;

class GenerateOrderCodeCommand 
{
    /**
     * @var string
     */
    public $seIssuingOffice;

    /**
     * @var string
     * whether it's a customer or vendor
     */
    public $userCode;

    

    /**
     * @var string
     */
    public $formType;

    public function __construct($seIssuingOffice, $userCode, $formType){
        $this->seIssuingOffice = $seIssuingOffice;
        $this->userCode = $userCode;
        $this->formType = $formType;
    }
}
