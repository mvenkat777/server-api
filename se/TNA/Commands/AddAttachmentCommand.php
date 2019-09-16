<?php

namespace Platform\TNA\Commands;

class AddAttachmentCommand 
{
	/**
	 * @var string/UUID
	 */
	public $tnaId;

	/**
	 * @var File
	 */
	public $attachment;

	public function __construct($data, $tnaId){
		$this->tnaId = $tnaId;
		$this->attachment = $data;
	}

}