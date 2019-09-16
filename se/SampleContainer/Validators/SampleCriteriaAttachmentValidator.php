<?php

namespace Platform\SampleContainer\Validators;

use Platform\App\Validation\DataValidator;

class SampleCriteriaAttachmentValidator extends DataValidator
{
	/**
	*@var array
	*/
	protected $rules = [];

    /**
     * The SampleCriteriaAttachment creation rules
     */
    public function setCreationRules()
    {
        $this->data = [
            'criteriaId' => 'required|exists:sample_criterias,id',
            'file' => 'required|json'
        ];

        return $this;
    }
}