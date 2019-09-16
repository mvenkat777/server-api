<?php

namespace Platform\Users\Validators;

use Platform\App\Validation\DataValidator;

class SignUp extends DataValidator
 {
 	protected $rules = [
 		'displayName' => 'required|max:70',
 		'email' => 'required|unique:users|email',
 		'password' => 'required|min:6',
 	];
 }
