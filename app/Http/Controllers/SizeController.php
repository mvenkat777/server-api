<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\Pom\Repositories\Contracts\SizeRepository;
use Platform\Pom\Transformers\SizeTransformer;
use Platform\Pom\Validators\SizeValidator;

class SizeController extends ApiController
{
    private $validator;
    private $size;

    public function __construct(
        SizeValidator $validator, 
        SizeRepository $size
    ) {
        parent::__construct(new Manager());

        $this->validator = $validator;
        $this->size = $size;
    }    

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->get('type');
        $size = $this->size->getAllSize($type);
        return $this->respondWithCollection($size, 
            new SizeTransformer, 
            'size'
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
        $this->validator->setCreateSizeRules()->validate($data);
        $size = $this->size->addSize($data);

        if ($size) {
             return $this->respondWithNewItem($size, new SizeTransformer, 'size');
        }

        return $this->respondWithError("Failed to add the new size . Please try again");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {
        $data = $request->all();
        $this->validator->setUpdateSizeRules()->validate($data);
        $size = $this->size->updateSize($code, $data);

        if ($size) {
             return $this->respondWithNewItem($size, new SizeTransformer, 'size');
        }

        return $this->respondWithError("Failed to update the size. Please try again");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $deleted = $this->product->deleteSize($code);

        if ($deleted) {
             return $this->respondOk("size deleted.");
        }

        return $this->respondWithError("Failed to delete the size . Please try again");
    }
}
