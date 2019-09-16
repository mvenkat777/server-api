<?php
namespace Platform\Form\Commands;

/**
* 
*/
class GetFormCommand
{
    public $type;

    function __construct($request)
    {
        $this->type = $request['type'];
        $this->id = isset($request['id']) ? $request['id'] : NULL;
    }
}