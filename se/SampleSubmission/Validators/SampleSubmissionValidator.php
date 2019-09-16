<?php

namespace Platform\SampleSubmission\Validators;

use Platform\App\Validation\DataValidator;

class SampleSubmissionValidator extends DataValidator
{
    /**
     * Set submit sample validation rules
     */
    public function setSampleSubmissionRules()
    {
        $this->rules = [
            'name' => 'required|max:255|unique:sample_submissions',
            'styleCode' => 'required',
            'type' => 'required',
            'customer' => 'required|exists:customers,id',
            'techpack' => 'required|exists:techpacks,id',
        ];

        return $this;
    }

    /**
     * Set submit sample validation rules
     */
    public function setSampleUpdationRules()
    {
        $this->rules = [
            'styleCode' => 'required',
            'type' => 'required',
            'customer' => 'required|exists:customers,id',
            'techpack' => 'required|exists:techpacks,id',
        ];

        return $this;
    }

    /**
     * Set add sample category validation rules
     */
    public function setAddCategoryRules()
    {
        $this->rules = [
        ];

        return $this;
    }

    /**
     * Set add sample category comment validation rules
     */
    public function setAddCategoryCommentRules()
    {
        $this->rules = [
        ];

        return $this;
    }
}
