<?php 

namespace Platform\Apps\Commands;

class DeleteAppCommand {
 
	public $id;
    function __construct($id)
    {
    	$this->id = $id;
    }


}