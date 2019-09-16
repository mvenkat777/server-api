<?php

namespace Platform\Tasks\Commands;

class RollbackTaskCommand 
{
    /**
     * @var string UUID
     */
    public $taskId;
    
	public function __construct($data, $taskId){
        $this->taskId = $taskId;
	}

}
