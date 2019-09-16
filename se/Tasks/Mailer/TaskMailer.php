<?php

namespace Platform\Tasks\Mailer;

use Platform\App\Mailer\Mailer;

class TaskMailer extends Mailer
{	 
	/**
	 * Send mail to assignee when new task is created
	 * 
	 * @param  User $user
	 * @param  array  $data
	 * @return mixed      
	 */
	public function taskWasCreated($user, $data = [])
	{
		$view = 'emails.tasks.NewTaskAssigned';
		$subject = 'New Task Has Been Assigned To You';

		return $this->sendTo($user, $subject, $view, $data);
	}

	/**
	 * Send Email to owner of task when task is submitted by assignee
	 * 
	 * @param  User $user
	 * @param  array  $data
	 * @return mixed     
	 */
	public function taskWasSubmitted($user, $data = [])
	{
		$view = 'emails.tasks.TaskApprovalMail';
		$subject = 'Request for Task Approval';
		return $this->sendTo($user, $subject, $view, $data);
	}

	/**
	 * Send mail to assignee when task is reviewed by ownerr
	 * 
	 * @param  User $user
	 * @param  array  $data
	 * @return mixed     
	 */
	public function taskWasArchived($user, $data = [])
	{
		$view = 'emails.tasks.TaskStatus';
		$subject = 'Task Status';
		return $this->sendTo($user, $subject, $view, $data);
	}

	/**
	 * Send mail to a particular task owner
	 * 
	 * @param  User $user
	 * @param  array  $data
	 * @return mixed     
	 */
	public function sendMailForTaskOwner($user, $data = [])
	{
		$view = 'emails.tasks.SendMailToTaskOwner';
		$subject = $data['taskName'];
		$content = $this->sendTo($user, $subject, $view, $data);
		// $success = \File::deleteDirectory($filePath);
		return $content;
	}

	/**
	 * Send mail to a particular task owner with attachements
	 * 
	 * @param  User $user
	 * @param  array  $data
	 * @return mixed     
	 */
	public function sendMailWithAttachements($user, $data = [])
	{
		$attachmentPath = [];
		$filePath = storage_path()."/".$data['taskId'];
		if (file_exists($filePath)) {
		} else {
		    mkdir($filePath);
		}
		foreach ($data['attachments'] as $key => $attachment) {
			$attachData = json_decode($attachment->data);
			$attachmentPath[] = storage_path()."/".$data['taskId']."/".$attachData->name;
			$myfile = fopen(storage_path()."/".$data['taskId']."/".$attachData->name, "w") or die("Unable to open file!");
		    $txt = file_get_contents($attachData->selfLink);
		    fwrite($myfile, $txt);
		}
		$view = 'emails.tasks.SendMailWithAttachements';
		$subject = $data['taskName'];
		$content = $this->sendToWithAttachment($user, $subject, $view, $data, $attachmentPath);
		$success = \File::deleteDirectory($filePath);
		return $content;
	}

	/**
	 * Send mail to a particular task owner with attachements
	 * 
	 * @param  User $user
	 * @param  array  $data
	 * @return mixed     
	 */
	public function sendMailWithAttachementsAndComments($user, $data = [])
	{
		$attachmentPath = [];
		$filePath = storage_path()."/".$data['taskId'];
		if (file_exists($filePath)) {
		} else {
		    mkdir($filePath);
		}
		foreach ($data['attachments'] as $key => $attachment) {
			$attachData = json_decode($attachment->data);
			$attachmentPath[] = storage_path()."/".$data['taskId']."/".$attachData->name;
			$myfile = fopen(storage_path()."/".$data['taskId']."/".$attachData->name, "w") or die("Unable to open file!");
		    $txt = file_get_contents($attachData->selfLink);
		    fwrite($myfile, $txt);
		}
		$view = 'emails.tasks.SendMailWithAttachementsAndComments';
		$subject = $data['taskName'];
		$content = $this->sendToWithAttachment($user, $subject, $view, $data, $attachmentPath);
		$success = \File::deleteDirectory($filePath);
		return $content;
	}

	/**
	 * Send email for new user if assignee is not found
	 * 
	 * @param  User $user
	 * @param  array  $data
	 * @return mixed      
	 */
	public function taskForNewUser($user, $data = [])
	{
		$view = 'emails.tasks.welcomeUser';
		$subject= 'Sourceeasy Account';
		return $this->sendTo($user, $subject, $view, $data);
	}

}
