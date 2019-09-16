<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\Pom\Repositories\Contracts\SizeRangeRepository;
use Platform\Pom\Transformers\SizeRangeTransformer;
use Platform\Pom\Validators\SizeRangeValidator;

class SizeRangeController extends ApiController
{

    private $validator;
    private $sizeRange;

    public function __construct(
        SizeRangeValidator $validator, 
        SizeRangeRepository $sizeRange
    ) {
        parent::__construct(new Manager());

        $this->validator = $validator;
        $this->sizeRange = $sizeRange;
    }  

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->get('type');
        $sizeRange = $this->sizeRange->getAllSizeRange($type);
        return $this->respondWithCollection($sizeRange, 
            new SizeRangeTransformer, 
            'sizeRange'
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
        $this->validator->setCreateSizeRangeRules()->validate($data);
        $sizeRange = $this->sizeRange->addSizeRange($data);

        if ($sizeRange) {
            return $this->respondWithNewItem($sizeRange, new SizeRangeTransformer, 'sizeRange');
        }

        return $this->respondWithError("Failed to add the new size range . Please try again");
    }

    /**
     * Get Range By Range Name
     *                     
     * @param  Request $request 
     * @param  string  $range   
     * @return mixed           
     */
    public function getByRange(Request $request, $range)
    {
        $sizeRange = $this->sizeRange->getBySizeRange($range);

        if ($sizeRange) {
            return $this->respondWithItem($sizeRange, new SizeRangeTransformer, 'sizeRange');
        } 
        return $this->respondWithError("size range not present. Please try again"); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $code
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {
        $data = $request->all();
        $this->validator->setUpdateSizeRangeRules()->validate($data);
        $sizeRange = $this->sizeRange->updateSizeRange($code, $data);

        if ($sizeRange) {
             return $this->respondWithNewItem($sizeRange, new SizeRangeTransformer, 'sizeRange');
        }

        return $this->respondWithError("Failed to update the sizeRange. Please try again");    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $deleted = $this->sizeRange->deleteSizeRange($code);

        if ($deleted) {
            return $this->respondOk("sizeRange deleted.");
        }

        return $this->respondWithError("Failed to delete the sizeRange . Please try again");
    }
}
