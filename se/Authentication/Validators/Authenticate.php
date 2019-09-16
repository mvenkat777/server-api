<?php 

namespace Platform\Authentication\Validators;

use Platform\App\Validation\DataValidator;

class Authenticate extends DataValidator
{
	protected $rules = [
		'email' => 'required|exists:users',
		'password' => 'required',
	];
}
