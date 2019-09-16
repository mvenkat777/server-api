<?php 

namespace Platform\Roles\Commands;

class ShowRoleByIdCommand {
 
	public $id;
    function __construct($id)
    {
    	$this->id = $id;
    }


}