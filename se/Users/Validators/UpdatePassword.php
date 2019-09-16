<?php

namespace Platform\Users\Validators;

use Platform\App\Validation\DataValidator;

class UpdatePassword extends DataValidator
 {
 	protected $rules = [
 		'password' => 'required|min:6'
 	];
 }
