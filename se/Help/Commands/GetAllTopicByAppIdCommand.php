<?php

namespace Platform\Help\Commands;

class GetAllTopicByAppIdCommand
{

    /**
     * @var integer
    */
    public $app_name;
    
    function __construct($app_name)
    {
        $this->app_name = $app_name;
    }


}