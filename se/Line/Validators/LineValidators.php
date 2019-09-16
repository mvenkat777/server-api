<?php

namespace Platform\Line\Validators;

use Platform\App\Validation\DataValidator;

class LineValidators extends DataValidator
{
	public function setCreationRules() {
		$this->rules = [
			'name' => 'required',
			'customerId' => 'required|exists:customers,id',
			'salesRepId' => 'required|exists:users,id',
			'productionLeadId' => 'required|exists:users,id',
			'productDevelopmentLeadId' => 'required|exists:users,id',
			'soTargetDate' => 'required',
			'deliveryTargetDate' => 'required',
		];

		return $this;
	}	


	public function setUpdationRules() {
		$this->rules = [
			'customerId' => 'required|exists:customers,id',
			'salesRepId' => 'required|exists:users,id',
			'productionLeadId' => 'required|exists:users,id',
			'soTargetDate' => 'required',
			'deliveryTargetDate' => 'required',
		];

		return $this;
	}	
}
