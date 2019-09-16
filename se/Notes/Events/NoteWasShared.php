<?php
namespace Platform\Notes\Events;

use Platform\Notes\Commands\ShareNoteCommand;

class NoteWasShared
{
	public $email;
	public $shareBy;
	public $note;

	function __construct($email , $shareBy, $note)
	{
		$this->email = $email;
		$this->shareBy = $shareBy;
		$this->note = $note;
	}
}