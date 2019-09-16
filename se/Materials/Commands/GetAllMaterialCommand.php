<?php  

namespace Platform\Materials\Commands;

class GetAllMaterialCommand {
 
	/**
	 * @var int
	 */
	public $item;
	
	/**
	 * @param array $data 
	 */
    function __construct($data)
    {
       $this->item = isset($data['item'])? $data['item']:100;
    }


}