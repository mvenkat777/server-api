<?php

namespace Platform\Pom\Repositories\Contracts;

interface ProductListRepository 
{
    public function model();

    /**
     * Add a new product
     *
     * @param mixed $data
     * @return mixed
     */
    public function addProduct($data);

    /**
     * Update a product
     *
     * @param mixed $data
     * @return mixed
     */
    public function updateProduct($id, $data);
}
