<?php 

namespace Platform\Apps\Commands;

class ShowAppByIdCommand {
 
	public $id;
    function __construct($id)
    {
    	$this->id = $id;
    }


}