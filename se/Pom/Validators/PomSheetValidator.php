<?php

namespace Platform\Pom\Validators;

use Platform\App\Validation\DataValidator;

class PomSheetValidator extends DataValidator 
{
	protected $rules = [];

    /**
     * Validation rules for adding a new pom sheet
     *
     * @return void
     */
    public function setAddPomSheetRowRule() {
        $this->rules= [
        	'pomId' => 'required|exists:poms,id',
            'qc' => 'required|boolean',
            'key' => 'required|boolean',
            'code' => 'required|unique:pom_sheets,code',
            // 'description' => 'required',
            // 'tol' => 'required',
            // 'data' => 'required',
        ];
        return $this;
    }    

    /**
     * validate rule for updating a new pom sheet
     *
     * @return void
     */
    public function setUpdatePomSheetRowRule() {
    	$this->rules= [
            'pomId' => 'required|exists:poms,id',
            'qc' => 'required|boolean',
            'key' => 'required|boolean',
            'code' => 'required',
            // 'description' => 'required',
            // 'tol' => 'required',
            // 'data' => 'required',
        ];
        return $this;
    }
}