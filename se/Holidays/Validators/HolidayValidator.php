<?php

namespace Platform\Holidays\Validators;

use Platform\App\Validation\DataValidator;

class HolidayValidator extends DataValidator 
{
	/**
	 * Set Create Holiday rules
	 */
	public function setCreateRules()
	{
		$this->rules = [
            "date" => "required|date"
		];

		return $this;
	}

}
