<?php

namespace Platform\Customer\Validators;

use Platform\App\Validation\DataValidator;

class UpdateCollabValidator extends DataValidator
{
	/**
	*@var array
	*/
	protected $rules = [
		'logo' => 'sometimes',
        'name' => 'sometimes',
        'sales_lead_id' => 'sometimes|exists:users,id',
	];

}
