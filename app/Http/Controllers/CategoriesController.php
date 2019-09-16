<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Platform\Pom\Validators\ProductCategoryValidator;
use Platform\Pom\Repositories\Contracts\ProductCategoryRepository;
use Platform\Pom\Transformers\ProductCategoryTransformer;
use League\Fractal\Manager;
use Platform\App\Helpers\Helpers;

class CategoriesController extends ApiController
{
    private $validator;
    private $category;

    public function __construct(
        ProductCategoryValidator $validator, 
        ProductCategoryRepository $category
    ) {
        parent::__construct(new Manager());

        $this->validator = $validator;
        $this->category = $category;
    }    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->category->getAllCategory();

        if ($categories->count() > 0) {
             return $this->respondWithCollection($categories, new ProductCategoryTransformer);
        }

        return $this->respondWithError("No categories added yet.");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->validator->setCreateCategoryRules()->validate($data);
        $category = $this->category->addCategory($data);

        if ($category) {
             return $this->respondWithNewItem($category, new ProductCategoryTransformer);
        }

        return $this->respondWithError("Failed to add the new category. Please try again");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {
        $data = $request->all();
        // $this->validator->setUpdateCategoryRules()->validate($data);
        $category = $this->category->updateCategory($code, $data);

        if ($category) {
             return $this->respondWithNewItem($category, new ProductCategoryTransformer);
        }

        return $this->respondWithError("Failed to update the category. Please try again");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $deleted = $this->category->delete($code);

        if ($deleted) {
             return $this->respondOk("Category deleted.");
        }

        return $this->respondWithError("Failed to delete the category. Please try again");
    }

    /**
     * Attach a product to a category
     *
     * @param mixed $categoryCode
     * @param mixed $productCode
     * @access public
     * @return void
     */
    public function attachProduct($categoryCode, $productCode) {
        $categories = $this->category->attachProduct($categoryCode, $productCode);

        if ($categories) {
            $response = [
                'code' => $categories->code . $categories->products[0]->code,
                'category' => Helpers::snakecaseToNormalcase($categories->category),
                'product' => Helpers::snakecaseToNormalcase($categories->products[0]->product),
            ];
            return $this->respondWithArray([
                'data' => $response
            ]);
        }
        return $this->respondWithError("Not able to attach the product to the given category. Please try again.");
    }    

    /**
     * Detach a product from a category
     *
     * @param mixed $categoryCode
     * @param mixed $productCode
     * @return mixed
     */
    public function detachProduct($categoryCode, $productCode) {
        $categories = $this->category->detachProduct($categoryCode, $productCode);

        if ($categories) {
            return $this->respondOk("Product detached from category.");
        }
        return $this->respondWithError("Not able to detach the product from the given category. Please try again.");
    }    
}

