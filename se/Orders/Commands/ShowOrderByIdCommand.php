<?php

namespace Platform\Orders\Commands;

class ShowOrderByIdCommand
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