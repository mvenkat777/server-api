<?php 

namespace Platform\Orders\Validators;

use Platform\App\Validation\DataValidator;

class Orders extends DataValidator
{
	protected $rules = [
		'code' => 'required|unique:customers',
		'customer_id'=>'required|exists:customers,id'
	];
}
