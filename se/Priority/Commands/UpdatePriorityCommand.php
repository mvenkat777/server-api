<?php  

namespace Platform\Priority\Commands;

class UpdatePriorityCommand {

    public $priority;
    public $id;
 

    function __construct($data , $id)
    {
        $this->priority = $data['priority'];
        $this->id = $id;
             
    }


}