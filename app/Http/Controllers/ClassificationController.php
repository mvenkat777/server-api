<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\Pom\Repositories\Contracts\ClassificationRepository;
use Platform\Pom\Transformers\ClassificationTransformer;
use Platform\Pom\Validators\ClassificationValidator;
use App\Http\Controllers\ApiController;

class ClassificationController extends ApiController
{
    private $validator;
    private $classification;

    public function __construct(
        ClassificationValidator $validator, 
        ClassificationRepository $classification
    ) {
        parent::__construct(new Manager());

        $this->validator = $validator;
        $this->classification = $classification;
    }    


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classification = $this->classification->getAllClassification();
        return $this->respondWithCollection($classification, 
            new ClassificationTransformer, 
            'classification'
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $this->validator->setCreateClassificationRules()->validate($data);
        $classification = $this->classification->addClassification($data);

        if ($classification) {
             return $this->respondWithNewItem($classification, new ClassificationTransformer, 'classification');
        }

        return $this->respondWithError("Failed to add the new Classification. Please try again");
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
        $this->validator->setUpdateClassificationRules()->validate($data);
        $classification = $this->classification->updateClassification($code, $data);

        if ($classification) {
             return $this->respondWithNewItem($classification, new ClassificationTransformer);
        }

        return $this->respondWithError("Failed to update the classification. Please try again");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->product->deleteClassification($code);

        if ($deleted) {
             return $this->respondOk("classification deleted.");
        }

        return $this->respondWithError("Failed to delete the classification. Please try again");
    }
}
