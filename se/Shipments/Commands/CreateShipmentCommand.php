<?php

namespace Platform\Shipments\Commands;



class CreateShipmentCommand{

	/**
	 * @var string
	 */
    public $shipmentType;

    /**
	 * @var string
	 */
	public $shippedDate;
	
	/**
	 * @var string
	 */
	public $shippedDestination;
	
	/**
	 * @var string
	 */
	public $itemDetails;
	
	/**
	 * @var string
	 */
	public $trackingId;
	
	/**
	 * @var string
	 */
	public $trackingProvider;
	
	/**
	 * @var string
	 */
	public $userId;

	/**
	 * @var string
	 */
	public $trackingStatus;
	
	/**
	 * @var string
	 */
	public $techpackID;
	
	/**
	 * @var string
	 */
	public $productId;


	function __construct($data)
    {	
    	$this->shipmentType = $data['shipmentType'];
        $this->shippedDate = $data['shippedDate'];
        $this->shippedFrom = $data['shippedFrom'];
        $this->shippedDestination  = $data['shippedDestination'];
        $this->itemDetails = json_encode($data['itemDetails']);
        $this->trackingId   = $data['trackingId'] ;
        $this->trackingProvider = is_null($data['trackingProvider'])? NULL : $data['trackingProvider'];
        $this->userId    = \Auth::user()->id;
        $this->trackingStatus = 'shipped';
        $this->techpackID = isset($data['techpackID'])? $data['techpackID']:NULL;
        $this->productId = isset($data['productId'])? $data['productId']:NULL;

    }


}