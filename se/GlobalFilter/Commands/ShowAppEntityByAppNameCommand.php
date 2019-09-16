<?php

namespace Platform\GlobalFilter\Commands;

class ShowAppEntityByAppNameCommand
{
    /**
     * @var string
    */
    public $appName;

    /**
     * @var array
     */
    public $data;
    
    function __construct($data)
    {
        $this->appName = $data['appName'];
        $this->data = $data;
        $this->data['item'] = isset($data['item'])? $data['item'] : config('constants.MaxListItemLimit');
    }
}