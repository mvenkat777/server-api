<?php

namespace Platform\Pom\Repositories\Eloquent;

use App\ProductType;
use Platform\App\Exceptions\SeException;
use Platform\App\Helpers\Helpers;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Pom\Repositories\Contracts\ProductTypeRepository;

class EloquentProductTypeRepository extends Repository implements ProductTypeRepository 
{
    /**
     * @return string
     */
	public function model(){
		return 'App\ProductType';
	}

    /**
     * Get All product type
     * 
     * @return mixed
     */
    public function getAllProductType()
    {
        return $this->model->orderBy('code')->get();
    }

	/**
     * Add a new product 
     *
     * @param mixed $data
     * @return mixed
     */
    public function addProductType($data) {
        $productType = $this->model->where('product_type', Helpers::toSnakecase($data['productType']))->first();
        if ($productType) {
            throw new SeException("product type already exist ", 422, 4220701);
        }   
        $data = [
            'code' => $this->generateProductTypeCode(),
            'product_type' => Helpers::toSnakecase($data['productType']),
        ];
        return $this->create($data);
    }    

    /**
     * Update a product 
     *
     * @param mixed $data
     * @return mixed
     */
    public function updateProductType($code, $data) {

        $productType = $this->model->where('product_type', Helpers::toSnakecase($data['productType']))->first();
        if ($productType) {
            throw new SeException("product type already exist ", 422, 4220701);
        }
        $data = [
            'product_type' => Helpers::toSnakecase($data['productType']),
        ];
        $updated = $this->update($data, $code, 'code');

        if ($updated) {
             return $this->model->where('code', $code)->first();
        }
        return $updated;
    }   

    /**
     * @return string
     */
    public function generateProductTypeCode() {
        $productList = $this->model->orderBy('code', 'desc')->get();
		if ($productList[0]->code == 99) {
            $code = str_pad($productList[1]->code + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $code = str_pad($productList[0]->code + 1, 2, '0', STR_PAD_LEFT);
        }        
        return $code;
    }    


}