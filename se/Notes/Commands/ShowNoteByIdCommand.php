<?php

namespace Platform\Notes\Commands;

class ShowNoteByIdCommand
{

    /**
     * @var integer
    */
    public $id;
    
    function __construct($id)
    {
        $this->id = $id;
        $this->user = \Auth::user()->id;
    }


}