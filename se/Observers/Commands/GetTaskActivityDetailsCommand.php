<?php
namespace Platform\Observers\Commands;

class GetTaskActivityDetailsCommand{

	/**
     * @var taskId
     */

    public $taskId;

    function __construct($request)
    {
    	$this->taskId = $request;
    }
}