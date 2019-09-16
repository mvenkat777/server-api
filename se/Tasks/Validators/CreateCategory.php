<?php 

namespace Platform\Tasks\Validators;

use Platform\App\Validation\DataValidator;

class CreateCategory extends DataValidator
{
	protected $rules = [
		"title" => 'required|unique:task_categories,title',
	];
}
