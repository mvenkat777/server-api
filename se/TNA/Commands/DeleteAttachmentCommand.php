<?php

namespace Platform\TNA\Commands;

class DeleteAttachmentCommand 
{
	/**
	 * @var string/UUID
	 */
	public $tnaId;

	public function __construct($tnaId){
		$this->tnaId = $tnaId;
	}

}