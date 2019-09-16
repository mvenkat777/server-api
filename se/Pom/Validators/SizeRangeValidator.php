<?php

namespace Platform\Pom\Validators;

use Platform\App\Validation\DataValidator;

class SizeRangeValidator extends DataValidator 
{
	protected $rules = [];

    /**
     * Validation rules for adding a new sizeRange 
     *
     * @return void
     */
    public function setCreateSizeRangeRules() {
        $this->rules= [
            // 'range' => 'required|unique:size_ranges,range',
            'value' => 'sometimes',
            'sizeTypeId' => 'required'
        ];
        return $this;
    }    

    /**
     * Validation rules while updating a sizeRange 
     *
     * @return void
     */
    public function setUpdateSizeRangeRules() {
        $this->rules= [
            // 'range' => 'required|unique:size_ranges,range',
            'sizeTypeId' => 'required',
            'value' => 'sometimes'
        ];
        return $this;
    } 
}