<?php

namespace Platform\Notes\Mailer;

use Platform\App\Mailer\Mailer;

class NoteMailer extends Mailer
{
	public function shareNoteMail($user, $data = [])
	{

		$view = 'emails.notes.shareNote';
		$subject = 'Note Share With You';

		return $this->sendToSharedNote($user, $subject, $view, $data);
	}

	public function CommentNoteMail($user, $data = [])
	{

		$view = 'emails.notes.noteComment';
		$subject = 'Comment on the Note';

		return $this->sendToSharedNote($user, $subject, $view, $data);
	}	
}
