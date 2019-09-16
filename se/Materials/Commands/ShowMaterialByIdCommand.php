<?php  

namespace Platform\Materials\Commands;

class ShowMaterialByIdCommand {
 
	public $id;
    function __construct($id)
    {
    	$this->id = $id;
    }


}