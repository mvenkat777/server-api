<?php

namespace Platform\Payment\Commands;

class GetRequestStatusCommand {

	/**
     * @var integer
    */
    public $status;
    
    /**
     * @var integer
    */
    public $id;

    /**
     * @var boolean
    */
    public $paymentStatus;
    
    function __construct($status,$id,$paymentStatus)
    {
        $this->status = $status;
        $this->id = $id;
        $this->paymentStatus = $paymentStatus;
    }


}