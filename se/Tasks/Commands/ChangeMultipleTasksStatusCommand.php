<?php

namespace Platform\Tasks\Commands;

class ChangeMultipleTasksStatusCommand 
{
    /**
     * @var array
     */
    public $taskIds;

    /**
     * @var string
     */
    public $action;

    /**
     * @var email
     */
    public $email;

	public function __construct($data, $action){
        $this->taskIds = $data['taskIds'];
        $this->action = $action;
        $this->email = isset($data['email']) ? $data['email'] : null;
	}

}
