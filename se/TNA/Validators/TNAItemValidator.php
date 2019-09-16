<?php

namespace Platform\TNA\Validators;

use Illuminate\Validation\Factory as Validator;
use Platform\App\Validation\DataValidator;

class TNAItemValidator extends DataValidator 
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

	/**
	 * @param Validator $validator 
	 */
	public function __construct(Validator $validator){
		$this->validator = $validator;
	}

	/**
	 * Set Validation ruel for tna item creation
	 */
	public function setCreateItemRule()
	{
		$this->rules = [	
		    'title' => 'required',
		    'plannedDate' => 'required|date',
		    'isMilestone' => 'required|boolean',
		    'representor' => 'required|email|exists:users,email',
		    'dependorId' => 'sometimes|exists:tna_items,id',
            'visibility' => 'sometimes|array',
            'templateId' => 'sometimes|integer|exists:tna_templates,id'
		];
		return $this;
	}
}
