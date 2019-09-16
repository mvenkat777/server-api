<?php 

namespace Platform\Notes\Validators;

use Platform\App\Validation\DataValidator;

class Notes extends DataValidator
{
	protected $rules = [
		'title'=>'required',
	    'description'=>'sometimes'
	];
}
