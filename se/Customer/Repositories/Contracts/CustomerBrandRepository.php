<?php

namespace Platform\Customer\Repositories\Contracts;

interface CustomerBrandRepository
{
    /**
     * Return the models
     * @return string
     */
	public function model();

    /**
     * Add Brand 
     * @param  array $data
     * @return App\Brand
     */
    public function addBrand($brand, $id);
}