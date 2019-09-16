<?php

namespace Platform\TNA\Commands;

class TNAArchiveActionCommand 
{
	/**
	 * @var Platform\TNA\Models\TNA
	 */
	public $tna;

	public function __construct($tna){
		$this->tna = $tna;
	}
}