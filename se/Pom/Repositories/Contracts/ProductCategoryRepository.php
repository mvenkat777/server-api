<?php

namespace Platform\Pom\Repositories\Contracts;

interface ProductCategoryRepository 
{
    public function model();

    /**
     * get All Category 
     * @return mixed
     */
    public function getAllCategory();
    
    /**
     * Add a new category
     *
     * @param mixed $data
     * @return mixed
     */
    public function addCategory($data);

    /**
     * Update a category
     *
     * @param mixed $data
     * @return mixed
     */
    public function updateCategory($id, $data);

    /**
     * Attach a product
     *
     * @param mixed $data
     * @return mixed
     */
    // public function attachProduct($categoryCode, $productCode);
}
