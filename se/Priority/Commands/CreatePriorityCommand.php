<?php  

namespace Platform\Priority\Commands;

class CreatePriorityCommand {

    public $priority;
 
    function __construct($data)
    {
        $this->priority = $data['priority'];       
    }


}