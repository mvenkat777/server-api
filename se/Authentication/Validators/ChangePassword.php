<?php 

namespace Platform\Authentication\Validators;

use Platform\App\Validation\DataValidator;

class ChangePassword extends DataValidator
{
	protected $rules = [
		'currentPassword' => 'required|min:6',
		'newPassword' => 'required|min:6'
	];
}
