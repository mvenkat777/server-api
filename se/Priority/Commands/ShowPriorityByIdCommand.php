<?php 

namespace Platform\Priority\Commands;

class ShowPriorityByIdCommand {
 
	public $id;
    function __construct($id)
    {
    	$this->id = $id;
    }


}