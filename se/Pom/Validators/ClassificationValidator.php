<?php

namespace Platform\Pom\Validators;

use Platform\App\Validation\DataValidator;

class ClassificationValidator extends DataValidator 
{
	protected $rules = [];

    /**
     * Validation rules for adding a new classification
     *
     * @return void
     */
    public function setCreateClassificationRules() {
        $this->rules= [
            'classification' => 'required|unique:classifications'
        ];
        return $this;
    }    

    /**
     * Validation rules while updating a classification
     *
     * @return void
     */
    public function setUpdateClassificationRules() {
        $this->rules= [
            'classification' => 'required|unique:classifications'
        ];
        return $this;
    } 
}