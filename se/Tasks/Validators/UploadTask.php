<?php 

namespace Platform\Tasks\Validators;

use Platform\App\Validation\DataValidator;

class UploadTask extends DataValidator
{
	protected $rules = [
		"title" => 'required',
		"description" => 'required',
		"task_deadline" => 'required',
		"priority" => 'required',
		"category" => 'required',
		"assignee" => 'required|email',
	];
}
