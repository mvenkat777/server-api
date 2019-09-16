<?php

namespace Platform\Form\Commands;

/**
* 
*/
class StoreFormCommand
{
    // public $billToAddress;
    // public $customerPoNumber;
    // public $countryOfOrigin;
    // public $countryOfOriginOfGoods;
    // public $finalDestinationOfGoods;
    // public $modeOfShipment;
    // public $orderDate;
    // public $deliveryDate;
    // public $portOfLoading;
    // public $cancelDate;
    // public $paymentTerms;
    public $data;
    public $creator;
    
    function __construct($request)
    {
        $this->data = $request;
        $this->creator = \Auth::user()->id;
    }
}