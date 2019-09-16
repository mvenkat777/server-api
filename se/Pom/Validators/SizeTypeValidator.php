<?php

namespace Platform\Pom\Validators;

use Platform\App\Validation\DataValidator;

class SizeTypeValidator extends DataValidator 
{
	protected $rules = [];

    /**
     * Validation rules for adding a new size type
     *
     * @return void
     */
    public function setCreateSizeTypeRules() {
        $this->rules= [
            'sizeType' => 'required|unique:size_types,size_type'
        ];
        return $this;
    }    

    /**
     * Validation rules while updating a size type
     *
     * @return void
     */
    public function setUpdateSizeTypeRules() {
        $this->rules= [
            'sizeType' => 'required|unique:size_types,size_type'
        ];
        return $this;
    } 
}