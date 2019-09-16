<?php
namespace Platform\Tasks\Commands;

class CreateCategoryCommand 
{
	/**
	 * @var string
	 */
	public $title;

	/**
	 * To validate data in handler
	 * @var array
	 */
	public $data;

	/**
	 * @param array $data
	 */
	public function __construct($data){
		// $this->title = isset($data[0]) ? $data[0] : NULL;
		$this->title = $data;
		$this->data = $data;
	}

}