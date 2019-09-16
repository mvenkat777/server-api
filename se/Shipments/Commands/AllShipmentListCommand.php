<?php

namespace Platform\Shipments\Commands;

class AllShipmentListCommand {

	/**
	*@param paginate 
	*/
	public $paginate;

    function __construct($data)
    {
       $this->paginate = is_null($data->get('item'))? 100:$data->get('item');
    }
}