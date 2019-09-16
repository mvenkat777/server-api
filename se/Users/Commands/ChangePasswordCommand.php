<?php

namespace Platform\Users\Commands;

class ChangePasswordCommand 
{
	/**
	 * @var string
	 */
	public $currentPassword;

	/**
	 * @var string
	 */
	public $newPassword;

	public function __construct($data){
		$this->currentPassword = $data['currentPassword'];
		$this->newPassword = $data['newPassword'];
	}

}