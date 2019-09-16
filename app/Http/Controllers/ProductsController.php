<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Platform\App\Helpers\Helpers;
use Platform\Pom\Repositories\Contracts\ProductListRepository;
use Platform\Pom\Transformers\ProductCategoryTransformer;
use Platform\Pom\Transformers\ProductListTransformer;
use Platform\Pom\Validators\ProductListValidator;

class ProductsController extends ApiController
{
    private $validator;
    private $product;

    public function __construct(
        ProductListValidator $validator, 
        ProductListRepository $product,
        Manager $fractal
    ) {
        parent::__construct(new Manager());

        $this->validator = $validator;
        $this->product = $product;
        $this->fractal = $fractal;
    }    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->product->getAllProductList();
        if ($products->count() > 0) {
             return $this->respondWithCollection($products, new ProductListTransformer);
        }

        return $this->respondWithError("No products added yet.");
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
        $this->validator->setCreateProductRules()->validate($data);
        $product = $this->product->addProduct($data);

        if ($product) {
             return $this->respondWithNewItem($product, new ProductListTransformer);
        }

        return $this->respondWithError("Failed to add the new product. Please try again");
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
        $this->validator->setUpdateProductRules()->validate($data);
        $product = $this->product->updateProduct($code, $data);

        if ($product) {
             return $this->respondWithNewItem($product, new ProductListTransformer);
        }

        return $this->respondWithError("Failed to update the product. Please try again");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $deleted = $this->product->delete($code);

        if ($deleted) {
             return $this->respondOk("Product deleted.");
        }

        return $this->respondWithError("Failed to delete the product. Please try again");
    }

     /**
     * List of all categories with products
     *
     * @return void
     */
    public function listPom() {
        $products = \App\ProductList::orderBy('product')->get();
        $categories = \App\ProductCategory::orderBy('code')->get();
        $productTypes = \App\ProductType::orderBy('code')->get();
        $data = [];
        foreach ($productTypes as $key => $productType) {
            $data[$key] = [
                'code' => $productType->code,
                'productType' => Helpers::snakecaseToNormalcase($productType->product_type)
            ];
        }

        $products = new Collection($products, new ProductListTransformer , 'product');
        $products = $this->fractal->createData($products);

        $categories = new Collection($categories, new ProductCategoryTransformer , 'product');
        $categories = $this->fractal->createData($categories);

        return $this->respondWithArray([
            'data' => ['products' => $products->toArray()['data'],
                'categories' => $categories->toArray()['data'],
                'productTypes' => $data
            ]
        ]);
    }
}
