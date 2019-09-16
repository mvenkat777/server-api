<?php

namespace Platform\Payment\Commands;

class DestroyPaymentOrderCommand {

	/**
	 * @var string
	 */
    public $id;

    function __construct($id)
    {
        $this->id = $id;
    }


}