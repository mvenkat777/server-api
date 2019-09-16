<?php

namespace Platform\Pom\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Pom\Repositories\Contracts\ProductCategoryRepository;
use App\ProductCategory;
use Platform\App\Exceptions\SeException;
use Platform\App\Helpers\Helpers;

class EloquentProductCategoryRepository extends Repository implements ProductCategoryRepository 
{

    /**
     * get All Category 
     * @return mixed
     */
    public function model(){
            return 'App\ProductCategory';
    }

    public function getAllCategory()
    {
        return $this->model->orderBy('code')->get();
    }

    public function getCategoryByName($category)
    {
        return $this->model->where('category', Helpers::toSnakecase($category))->first();
    }

    /**
     * Add a new product category
     *
     * @param mixed $data
     * @return mixed
     */
    public function addCategory($data) {
        $category = $this->model->where('category', Helpers::toSnakecase($data['category']))->first();
        if ($category) {
            throw new SeException("category already exist ", 422, 4220704);
        }
        $data = [
            'code' => $this->generateCategoryCode(),
            'category' => Helpers::toSnakecase($data['category']),
            'description' => isset($data['description']) ? $data['description'] : '',
            'classification_code' => isset($data['classificationCode']) ? $data['classificationCode'] : NULL,
        ];
        return $this->create($data);
    }    

    /**
     * Update a product category
     *
     * @param mixed $data
     * @return mixed
     */
    public function updateCategory($code, $data) {
        $category = $this->model->where('category', Helpers::toSnakecase($data['category']))->first();
        if ($category) {
            throw new SeException("category already exist ", 422, 4220704);
        }
        $data = [
            'category' => Helpers::toSnakecase($data['category']),
            'description' => isset($data['description']) ? $data['description'] : '',
            'classification_code' => isset($data['classificationCode']) ? $data['classificationCode'] : NULL,
        ];
        $updated = $this->update($data, $code, 'code');

        if ($updated) {
             return $this->model->where('code', $code)->first();
        }
        return $updated;
    }    

    // public function attachProduct($categoryCode, $productCode) {
    //     $category = $this->model->where('code', $categoryCode)->first();
    //     if ($category) {
    //         $product = \App\ProductList::where('code', $productCode)
    //                                      ->first();
    //         if ($product) {
    //             $category->products()->sync([ 
    //                 $productCode => ['code' => $categoryCode . $productCode] 
    //             ], false);
    //             return $category->with([
    //                 'products' => function ($query) use ($productCode) {
    //                     $query->where('product_lists.code', $productCode);
    //                 }
    //             ])->where('code', $categoryCode)->first();
    //         }
    //         throw new SeException("Product with that code not found.", 404);
    //     }
    //     throw new SeException("Category with that code not found.", 404);
    // }    

    // public function detachProduct($categoryCode, $productCode) {
    //     $category = $this->model->where('code', $categoryCode)->first();
    //     if ($category) {
    //         $product = \App\ProductList::where('code', $productCode)
    //                                      ->first();
    //         if ($product) {
    //             $category->products()->detach([$productCode]);
    //             return true;
    //         }
    //         throw new SeException("Product with that code not found.", 404);
    //     }
    //     throw new SeException("Category with that code not found.", 404);
    // }    


    /**
     * Generates category code
     *
     * @return string
     */
    public function generateCategoryCode() {
        $category = $this->model->orderBy('code', 'desc')->get();
        if ($category[0]->code == 99) {
            $code = str_pad($category[1]->code + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $code = str_pad($category[0]->code + 1, 2, '0', STR_PAD_LEFT);
        }        
        return $code;
    }    

}
