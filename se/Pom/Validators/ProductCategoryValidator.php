<?php 

namespace Platform\Pom\Validators;

use Platform\App\Validation\DataValidator;

class ProductCategoryValidator extends DataValidator
{
    protected $rules = [];

    /**
     * Validation rules for adding a new category
     *
     * @return void
     */
    public function setCreateCategoryRules() {
        $this->rules= [
            'category' => 'required|unique:product_categories',
            'classificationCode' => 'sometimes|exists:classification,code'
        ];
        return $this;
    }    

    /**
     * Validation rules while updating a category
     *
     * @return void
     */
    public function setUpdateCategoryRules() {
        $this->rules= [
            'category' => 'required|unique:product_categories',
            'classificationCode' => 'sometimes|exists:classification,code'
        ];
        return $this;
    }    

}
