<?php
namespace Platform\Form\Commands;

/**
* 
*/
class UpdateFormCommand
{
    public $data;
    public $creator;
    
    function __construct($request)
    {
        $this->data = $request;
        $this->creator = \Auth::user()->id;
    }
}