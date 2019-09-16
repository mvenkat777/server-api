<?php 

namespace Platform\Tasks\Validators;

use Platform\App\Validation\DataValidator;

class CreateTag extends DataValidator
{
	protected $rules = [
		"title" => 'required|unique:task_tags,title',
	];
}
