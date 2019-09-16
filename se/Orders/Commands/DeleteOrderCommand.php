<?php

namespace Platform\Orders\Commands;

class DeleteOrderCommand {

	/**
	 * @var string
	 */
    public $id;

    function __construct($id)
    {
        $this->id = $id;
    }


}