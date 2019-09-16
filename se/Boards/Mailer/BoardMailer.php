<?php

namespace Platform\Boards\Mailer;

use Platform\App\Mailer\Mailer;

class BoardMailer extends Mailer
{
	/**
	 * Notify a user to collaborate on a Board
	 * @param  App\User  $user
	 * @param  array  $data
	 * @param  boolean $newUser
	 * @return mixed
	 */
	public function invite($user, $data, $newUser = false)
	{
		if ($newUser) {
			$view = 'emails.boards.inviteNewUser';
		} else {
			$view = 'emails.boards.invite';
		}

		$subject = $data['displayName'] . " has invited you to collaborate on a Board!";

		return $this->sendTo($user, $subject, $view, $data);
	}
}
