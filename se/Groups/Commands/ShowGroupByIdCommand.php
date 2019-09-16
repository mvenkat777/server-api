<?php  

namespace Platform\Groups\Commands;

class ShowGroupByIdCommand {
 
	public $id;
    function __construct($id)
    {
    	$this->id = $id;
    }


}