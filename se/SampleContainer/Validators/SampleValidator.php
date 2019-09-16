<?php

namespace Platform\SampleContainer\Validators;

use Platform\App\Validation\DataValidator;

class SampleValidator extends DataValidator
{
	/**
	*@var array
	*/
	protected $rules = [];

    /**
     * Validation rules for creating a sample
     */
    public function setCreationRules()
    {
        $this->rules = [
            'sampleContainerId' => 'required|exists:sample_containers,id',
            'title' => 'required|max:255',
            'type' => 'required|max:70',
            'image' => 'required',
            'sentDate' => 'sometimes|date',
            'receivedDate' => 'sometimes|date',
            'vendorId' => 'sometimes|exists:vendors,id',
            'weightOrQuality' => 'sometimes|max:20',
            'fabricOrContent' => 'sometimes|max:100',
        ];
        return $this;
    }

    /**
     * Validation rules for updating a sample
     */
    public function setUpdationRules()
    {
        $this->rules = [
            'sampleContainerId' => 'required|exists:sample_containers,id',
            'sampleId' => 'required|exists:samples,id',
            'title' => 'required|max:255',
            'type' => 'required|max:70',
            'image' => 'required',
            'sentDate' => 'sometimes|date',
            'receivedDate' => 'sometimes|date',
            'vendorId' => 'sometimes|exists:vendors,id',
            'weightOrQuality' => 'sometimes|max:20',
            'fabricOrContent' => 'sometimes|max:100',
        ];
        return $this;
    }
}