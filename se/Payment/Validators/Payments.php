<?php 

namespace Platform\Payment\Validators;

use Platform\App\Validation\DataValidator;

class Payments extends DataValidator
{
	protected $rules = [
		'email' => 'required',
		'productName' => 'required',
		'amount' => 'required',
	];
}
