<?php

namespace Platform\Line\Commands;

class CreateNewLineCommand 
{
	/**
	 * @var array
	 */
	public $data;

	/**
	 * @param array $data
	 * @return void
	 */
	public function __construct($data){
            $this->data = $data;
	}
}
