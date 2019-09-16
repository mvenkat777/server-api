<?php

namespace Platform\Contacts\Commands;

class DeleteContactCommand {

	/**
	 * @var string
	 */
    public $id;

    function __construct($id)
    {
        $this->id = $id;
    }
}