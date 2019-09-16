<?php

namespace Platform\Customer\Repositories\Eloquent;

use App\Customer;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Customer\Repositories\Contracts\CustomerBrandRepository;

class EloquentCustomerBrandRepository extends Repository implements CustomerBrandRepository
{
     /**
     * Return the models
     * @return string
     */
    public function model()
    {
        return 'App\CustomerBrand';
    }

   /**
    * @param string $brand 
    * @param int $id    
    */
    public function addBrand($brand, $id)
    {
        $brand = [
            'brand' => $brand,
            'customer_id' => $id
        ];
        return $this->create($brand);
    }

    /**
    * @param string $brand 
    * @param int $id    
    */
    public function updateBrand($brand, $customerId)
    {   
        $brand1 = [
            'brand' => $brand,
            'customer_id' => $customerId
        ];
        return $this->create($brand1);
    }

    /**
     * @param   $brandId 
     * @return  boolean
     */
    public function delete($brandId)
    {
        $customer = $this->model->where('id', '=', $brandId)->first();
        return $customer->delete();
    }

    /**
     * @param   $brandId 
     * @return  boolean
     */
    public function deleteAll($customerId)
    {
        return $this->model->where('customer_id', '=', $customerId)->delete();
    }
}