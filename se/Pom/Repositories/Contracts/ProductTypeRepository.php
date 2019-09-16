<?php

namespace Platform\Pom\Repositories\Contracts;

interface ProductTypeRepository 
{
    public function model();


    /**
     * Get All product type
     * 
     * @return mixed
     */
    public function getAllProductType();

    /**
     * Add a new product type
     *
     * @param mixed $data
     * @return mixed
     */
    public function addProductType($data);
    
    /**
     * Update a product type
     *
     * @param mixed $data
     * @return mixed
     */
    public function updateProductType($id, $data);
}
