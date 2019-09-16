<?php

namespace Platform\Line\Validators;

use Platform\App\Validation\DataValidator;

class StyleValidators extends DataValidator
{
    public function setCreationRules() {
        $this->rules = [
            'name' => 'required|max:255',
            'techpackId' => 'sometimes|exists:techpacks,id',
            'productBrief' => 'sometimes',
            'customerStyleCode' => 'sometimes|max:20',
            'tnaId' => 'sometimes|exists:tna,id',
            'sampleSubmissionId' => 'sometimes|exists:sample_submissions,id',
        ];

        return $this;
    }	


    public function setUpdationRules() {
        $this->rules = [
            'name' => 'sometimes|max:255',
            'techpackId' => 'sometimes|exists:techpacks,id',
            'productBrief' => 'sometimes',
            'customerStyleCode' => 'sometimes|max:20',
            'tnaId' => 'sometimes|exists:tna,id',
            'sampleSubmissionId' => 'sometimes|exists:sample_submissions,id',
        ];

        return $this;
    }	
}
