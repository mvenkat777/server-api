<?php

namespace Platform\TNA\Commands;

class DeleteTNACommand 
{
	/**
	 * @var string UUID 
	 */
	public $tnaId;

	public function __construct($id){
		$this->tnaId = $id;
	}
}