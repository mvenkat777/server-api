<?php 

namespace Platform\Roles\Commands;

class GetAllUserRolesCommand {
 
	public $id;
    function __construct($id)
    {
    	$this->id = $id;
    }


}