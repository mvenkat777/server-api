<?php

namespace Platform\Pom\Repositories\Eloquent;

use App\ProductList;
use App\ProductType;
use Platform\App\Exceptions\SeException;
use Platform\App\Helpers\Helpers;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Pom\Repositories\Contracts\ProductListRepository;

class EloquentProductListRepository extends Repository implements ProductListRepository 
{

    public function model(){
        return 'App\ProductList';
    }
    
    /**
     * get All ProductList 
     * @return mixed
     */
    public function getAllProductList()
    {
        return $this->model->orderBy('code')->with(['productType'])->get();
    }

    public function getProductByName($product)
    {
        return $this->model->where('product', Helpers::toSnakecase($product))->first();
    }
    /**
     * Add a new product 
     *
     * @param mixed $data
     * @return mixed
     */
    public function addProduct($data) {
        $product = $this->model->where('product', Helpers::toSnakecase($data['product']))->first();
        if ($product) {
            throw new SeException("product already exist ", 422, 4220702);
        }
        $data = [
            'code' => $this->generateProductCode(),
            'product_type_code' => $data['productTypeCode'],
            'product' => Helpers::toSnakecase($data['product']),
            'description' => isset($data['description']) ? $data['description'] : '',
        ];
        return $this->create($data);
    }    

    /**
     * Update a product 
     *
     * @param mixed $data
     * @return mixed
     */
    public function updateProduct($code, $data) {
        $product = $this->model->where('product', Helpers::toSnakecase($data['product']))->first();
        if ($product) {
            throw new SeException("product already exist ", 422, 4220702);
        }
        $data = [
            'product' => Helpers::toSnakecase($data['product']),
            'product_type_code' => $data['productTypeCode'],
            'description' => isset($data['description']) ? $data['description'] : '',
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
    public function generateProductCode() {
        $product = $this->model->orderBy('code', 'desc')->get();
        if ($product[0]->code == 999) {
            $code = str_pad($product[1]->code + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $code = str_pad($product[0]->code + 1, 3, '0', STR_PAD_LEFT);
        }        
        return $code;
    }    
}
