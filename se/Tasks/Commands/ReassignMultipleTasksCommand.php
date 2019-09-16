<?php

namespace Platform\Tasks\Commands;

use Platform\App\Helpers\Helpers;

class ReassignMultipleTasksCommand 
{
	/**
	 * @var array
	 */
	public $list;


	public function __construct($data){
		$this->list = $data['list'];
	}

}