<?php

namespace Platform\SampleContainer\Validators;

use Platform\App\Validation\DataValidator;

class SampleContainerValidator extends DataValidator
{
	/**
	*@var array
	*/
	protected $rules = [];

    /**
     * Validation rules for sample container creation
     */
    public function setCreationRules()
    {
        $this->rules = [
            'techpackId' => 'required|exists:techpacks,id',
        ];
        return $this;
    }
}