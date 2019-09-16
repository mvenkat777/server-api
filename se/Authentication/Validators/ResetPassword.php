<?php 

namespace Platform\Authentication\Validators;

use Platform\App\Validation\DataValidator;

class ResetPassword extends DataValidator
{
	protected $rules = [
		'token' => 'required',
		'password' => 'required|min:6',
		'email' => 'required|email'
	];
}
