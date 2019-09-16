<?php

namespace Platform\TNA\Commands;

class ChangeStateCommand 
{
	/**
	 * @var string UUID
	 */
	public $tnaId;

	/**
	 * @var string
	 */
	public $tnaState;

	/**
	 * @var boolean
	 */
	public $isPublished;

	public function __construct($data, $tnaId, $state){
		$this->tnaId = $tnaId;
		$this->tnaState = $state;
		$this->isPublished = (strtolower($state) == 'unpublish') ? false : true;
	}

}