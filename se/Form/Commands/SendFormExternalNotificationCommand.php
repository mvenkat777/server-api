<?php
namespace Platform\Form\Commands;

/**
* 
*/
class SendFormExternalNotificationCommand
{
    public $data;
    public $action;
    function __construct($data, $action)
    {
        $this->data = $data;
        $this->action = $action;
    }
}