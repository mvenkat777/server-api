<?php

namespace Platform\Help\Commands;

class ShowHelpBySlugCommand
{

    /**
     * @var integer
    */
    public $slug;
    
    function __construct($slug)
    {
        $this->slug = $slug;
    }


}