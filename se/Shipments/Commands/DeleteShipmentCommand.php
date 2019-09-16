<?php

namespace Platform\Shipments\Commands;

class DeleteShipmentCommand {

	/**
	 * @var string
	 */
    public $id;

    function __construct($id)
    {
        $this->id = $id;
    }


}