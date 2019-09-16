<?php

namespace Platform\Pom\Validators;

use Platform\App\Validation\DataValidator;

class SizeValidator extends DataValidator 
{
	protected $rules = [];

    /**
     * Validation rules for adding a new size 
     *
     * @return void
     */
    public function setCreateSizeRules() {
        $this->rules= [
            'size' => 'required|unique:sizes,size',
            'sizeTypeId' => 'sometimes|exists:size_types,id'
        ];
        return $this;
    }    

    /**
     * Validation rules while updating a size 
     *
     * @return void
     */
    public function setUpdateSizeRules() {
        $this->rules= [
            'size' => 'required|unique:sizes,size',
            'sizeTypeId' => 'sometimes|exists:size_types,id'
        ];
        return $this;
    } 

}