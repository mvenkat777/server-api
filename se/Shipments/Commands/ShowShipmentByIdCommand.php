<?php

namespace Platform\Shipments\Commands;

class ShowShipmentByIdCommand
{

    /**
     * @var integer
    */
    public $id;
    
    function __construct($id)
    {
        $this->id = $id;
    }


}