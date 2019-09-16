<?php 

namespace Platform\Roles\Commands;

class DeleteRoleCommand {
 
	public $id;
    function __construct($id)
    {
    	$this->id = $id;
    }


}