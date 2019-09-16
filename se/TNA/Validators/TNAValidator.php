<?php

namespace Platform\TNA\Validators;

use Illuminate\Validation\Factory as Validator;
use Platform\App\Validation\DataValidator;

class TNAValidator extends DataValidator 
{
	/**
	*@var array
	*/
	protected $rules = [];

	/**
	 * Inherites property, but needed for setting rules and calling validate()
	 * @var Validator
	 */
	protected $validator;

	public function __construct(Validator $validator){
		$this->validator = $validator;
	}

	/**
	 * Set Validation rule for creating TNA
	 */
	public function setCreateTNARule()
	{
		$this->rules = [
			'orderId' => 'sometimes|exists:orders,id',
		    'techpackId' => 'sometimes|exists:techpacks,id',
		    'customerId' => 'sometimes|exists:customers,id',
		    'vendorId' => 'sometimes|array|exists:vendors,id',
		    'title' => 'required',
		    'startDate' => 'required|date',
		    'targetDate' => 'required|date',
		    'representor' => 'required|email|exists:users,email',
		    // 'styleCode' => 'required'
		];
		return $this;
	}

	/**
	 * Set Validation rule for update TNA
	 */
	public function setRulesForUpdateTNA()
	{
		$this->rules = [
			'customerId' => 'required|exists:customers,id',
			'title' => 'required',
			'startDate' => 'required|date',
		    'targetDate' => 'required|date'
		];
		return $this;
	}
}
