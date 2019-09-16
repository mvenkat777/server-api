<?php 

namespace Platform\Orgs\Commands;

class DeleteOrgCommand {
 
	public $id;
    function __construct($id)
    {
    	$this->id = $id;
    }


}