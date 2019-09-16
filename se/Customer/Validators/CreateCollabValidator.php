<?php

namespace Platform\Customer\Validators;

use Platform\App\Validation\DataValidator;

class CreateCollabValidator extends DataValidator
{
	/**
	*@var array
	*/
	protected $rules = [
		'url' => 'required|unique:collabs|min:2',
		'logo' => 'sometimes',
        'name' => 'sometimes',
        'sales_lead_id' => 'sometimes|exists:users,id',
	];

}
