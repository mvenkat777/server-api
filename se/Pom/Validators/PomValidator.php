<?php

namespace Platform\Pom\Validators;

use Platform\App\Validation\DataValidator;

class PomValidator extends DataValidator 
{
	protected $rules = [];

    /**
     * Validation rules for adding a new pom
     *
     * @return void
     */
    public function setCreatePomRules() {
        $this->rules= [
            'categoryCode' => 'required|exists:product_categories,code',
            'sizeTypeId' => 'required|exists:size_types,id',
            'productTypeCode' => 'required|exists:product_types,code',
            'sizeRangeName' => 'required',
            'sizeRangeValue' => 'required',
            'baseSize' => 'required',
        ];
        return $this;
    }    
}