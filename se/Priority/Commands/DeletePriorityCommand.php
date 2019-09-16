<?php 

namespace Platform\Priority\Commands;

class DeletePriorityCommand {
 
	public $id;
    function __construct($id)
    {
    	$this->id = $id;
    }


}