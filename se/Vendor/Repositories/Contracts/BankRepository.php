<?php

namespace Platform\Vendor\Repositories\Contracts;

interface BankRepository
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
    public function addBankDetails($brand);
}