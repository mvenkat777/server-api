<?php

namespace Platform\Contacts\Commands;

class ShowContactByIdCommand
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