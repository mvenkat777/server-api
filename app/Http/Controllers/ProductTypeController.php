<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\Pom\Repositories\Contracts\ProductTypeRepository;
use Platform\Pom\Transformers\ProductTypeTransformer;
use Platform\Pom\Validators\ProductTypeValidator;

class ProductTypeController extends ApiController
{
    private $validator;
    private $product;

    public function __construct(
        ProductTypeValidator $validator, 
        ProductTypeRepository $productType
    ) {
        $this->validator = $validator;
        $this->productType = $productType;
        parent::__construct(new Manager());
    }    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productTypes = $this->productType->getAllProductType();
        if ($productTypes->count() > 0) {
             return $this->respondWithCollection($productTypes, new ProductTypeTransformer);
        }

        return $this->respondWithError("No product type added yet.");
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
        $this->validator->setCreateProductTypeRules()->validate($data);
        $productType = $this->productType->addProductType($data);

        if ($productType) {
             return $this->respondWithNewItem($productType, new ProductTypeTransformer);
        }

        return $this->respondWithError("Failed to add the new product type. Please try again");
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
        $this->validator->setUpdateProductTypeRules()->validate($data);
        $productType = $this->productType->updateProductType($code, $data);

        if ($productType) {
             return $this->respondWithNewItem($productType, new ProductTypeTransformer);
        }

        return $this->respondWithError("Failed to update the product type. Please try again");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $deleted = $this->productType->delete($code);

        if ($deleted) {
             return $this->respondOk("product type deleted.");
        }

        return $this->respondWithError("Failed to delete the product type. Please try again");
    }
}
