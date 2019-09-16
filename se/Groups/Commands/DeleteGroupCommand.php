<?php 

namespace Platform\Groups\Commands;

class DeleteGroupCommand {
 
	public $id;
    function __construct($id)
    {
    	$this->id = $id;
    }


}