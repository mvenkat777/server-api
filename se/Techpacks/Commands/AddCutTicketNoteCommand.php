<?php

namespace Platform\Techpacks\Commands;

class AddCutTicketNoteCommand
{
	/**
	 * @var string
	 */
	public $techpackId;

	/**
	 * @var string
	 */
	public $note;

	/**
	 * @var json
	 */
	public $image;

	/**
	 * @param string $techpackId    
	 * @param array $cutTicketNote
	 */
	public function __construct($techpackId, $cutTicketNote) {
		$this->techpackId = $techpackId;
		$this->note = isset($cutTicketNote['note'])? $cutTicketNote['note'] : '';
		$this->image = isset($cutTicketNote['image'])? $cutTicketNote['image'] : [];
	}	
}
		
		
