<?php 

namespace Platform\Orgs\Commands;

class ShowOrgByIdCommand {
 
	public $id;
    function __construct($id)
    {
    	$this->id = $id;
    }


}