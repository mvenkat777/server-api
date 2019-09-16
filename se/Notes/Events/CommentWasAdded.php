<?php
namespace Platform\Notes\Events;

use Platform\Notes\Commands\ShareNoteCommand;

class CommentWasAdded
{
	public $email;
	public $commentedBy;
	public $note;

	function __construct($email , $commentedBy, $note)
	{
		$this->email = $email;
		$this->commentedBy = $commentedBy;
		$this->note = $note;
	}
}