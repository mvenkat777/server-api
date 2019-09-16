<?php

namespace Platform\SampleContainer\Validators;

use Platform\App\Validation\DataValidator;

class SampleCriteriaCommentValidator extends DataValidator
{
	/**
	*@var array
	*/
	protected $rules = [];

	/**
     * The SampleCriteriaComment creation rules
     */
    public function setCreationRules()
    {
        $this->data = [
            'criteriaId' => 'required|exists:sample_criterias,id',
            'comment' => 'required|string'
        ];

        return $this;
    }
    }