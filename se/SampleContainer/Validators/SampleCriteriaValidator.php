<?php

namespace Platform\SampleContainer\Validators;

use Platform\App\Validation\DataValidator;

class SampleCriteriaValidator extends DataValidator
{
	/**
	*@var array
	*/
	protected $rules = [];

    /**
     * Sample Criteria creation rules
     */
    public function setCreationRules()
    {
        $this->rules = [
            'sampleId' => 'required|exists:samples,id',
            'criteria' => 'required|max:100',
            'description' => 'sometimes|string',
            'note' => 'sometimes|string',
        ];
        return $this;
    }

    /**
     * Sample Criteria updation rules
     */
    public function setUpdationRules()
    {
        $this->rules = [
            'sampleId' => 'required|exists:samples,id',
            'criteria' => 'sometimes|max:100',
            'description' => 'sometimes|string',
            'note' => 'sometimes|string',
        ];
        return $this;
    }
}
