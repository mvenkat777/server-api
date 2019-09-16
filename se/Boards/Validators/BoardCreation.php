<?php 

namespace Platform\Boards\Validators;

use Platform\App\Validation\DataValidator;

class BoardCreation extends DataValidator
{
	protected $rules = [
		"name"        => "required",
		"description" => "required",
		"category"    => "required",
	];		
}