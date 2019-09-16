<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\Pom\Repositories\Contracts\SizeTypeRepository;
use Platform\Pom\Transformers\SizeTypeTransformer;
use Platform\Pom\Validators\SizeTypeValidator;
use App\Http\Controllers\ApiController;

class SizeTypeController extends ApiController
{
    private $validator;
    private $sizeType;

    public function __construct(
        SizeTypeValidator $validator, 
        SizeTypeRepository $sizeType
    ) {
        parent::__construct(new Manager());

        $this->validator = $validator;
        $this->sizeType = $sizeType;
    }    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sizeType = $this->sizeType->getAllSizeType();
        return $this->respondWithCollection($sizeType, 
            new SizeTypeTransformer, 
            'sizeType'
        );
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
        $this->validator->setCreateSizeTypeRules()->validate($data);
        $sizeType = $this->sizeType->addSizeType($data);

        if ($sizeType) {
             return $this->respondWithNewItem($sizeType, new SizeTypeTransformer, 'sizeType');
        }

        return $this->respondWithError("Failed to add the new size type. Please try again");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->validator->setUpdateSizeTypeRules()->validate($data);
        $sizeType = $this->sizeType->updateSizeType($id, $data);

        if ($sizeType) {
             return $this->respondWithNewItem($sizeType, new SizeTypeTransformer, 'sizeType');
        }

        return $this->respondWithError("Failed to update the size type. Please try again");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->product->deleteSizeType($code);

        if ($deleted) {
             return $this->respondOk("size type deleted.");
        }

        return $this->respondWithError("Failed to delete the size type. Please try again");
    }
}
