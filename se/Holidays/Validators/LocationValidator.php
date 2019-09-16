<?php

namespace Platform\Holidays\Validators;

use Platform\App\Validation\DataValidator;

class LocationValidator extends DataValidator 
{
	/**
	*@var array
	*/
	protected $rules = [];

	/**
	 * Set Create Location rules
	 */
	public function setCreateRules()
	{
		$this->rules = [
			"city" => "required|string",
			"state" => "required|string",
			"country" => "required|string",
			"code" => "required|string|unique:locations",
            "postalCode" => "required|integer",
            "address" => "sometimes|string"
		];

		return $this;
	}

	/**
	 * Set Update Location rules
	 */
	public function setUpdateRules()
	{
		$this->rules = [
			"city" => "required|string",
			"state" => "required|string",
			"country" => "required|string",
            "postalCode" => "required|integer",
            "address" => "sometimes|string"
		];

		return $this;
	}

}
