<?php

namespace Platform\Shipments\Commands;

class UpdateShipmentCommand {

    /**
    * @var string
    */
    public $id;

    /**
    * @var string
    */
    public $trackingStatus;

   function __construct($data, $id)
    {
        $this->id = $id;
        $this->trackingStatus = $data['trackingStatus'];
    }


} 