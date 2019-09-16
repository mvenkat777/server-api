<?php

namespace Platform\Users\Mailer;

use Platform\App\Mailer\Mailer;

class UserMailer extends Mailer
{
	public function welcome($user, $data = [])
	{

		$view = 'emails.users.welcome';
		$subject = 'Sourceeasy account is setup for you!';

		return $this->sendToUser($user, $subject, $view, $data);
	}

	public function resetPassword($user, $data = [])
	{
		$view = 'emails.users.resetPassword';
		$subject = 'Soureeasy Password Reset';
		
		return $this->sendToUser($user, $subject, $view, $data);
	}

	public function verifyUser($user, $data = [])
	{

		$view = 'emails.users.verifyUser';
		$subject = 'Welcome to Sourceeasy. Please activate your account!';

		return $this->sendToUser($user, $subject, $view, $data);
	}

	public function createdUser($user , $data = [])
	{
		$view = 'emails.users.welcomeNewUser';
		$subject = 'Sourceeasy Platform Login Info';

		return $this->sendToUser($user, $subject, $view, $data);
	}

}
