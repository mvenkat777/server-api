<?php

namespace Platform\Vendor\Repositories\Contracts;

interface VendorRepository
{
    /**
     * Return the models
     * @return string
     */
	public function model();

    /**
     * Create a new Vendor
     * @param  array $data
     * @return App\Vendor
     */
    public function createVendor($data);

    /**
     * @return mix
     */
    public function getAllVendor($data);

    /**
     * @param  vedor $id 
     * @return boolean
     */
    public function deleteVendor($id);

    /**
     * @param  search $data 
     * @return mix       
     */
    public function filterVendor($data);
}