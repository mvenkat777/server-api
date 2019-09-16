<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Holidays\Commands\AddLocationCommand;
use Platform\Holidays\Commands\DeleteLocationCommand;
use Platform\Holidays\Commands\UpdateLocationCommand;
use Platform\Holidays\Repositories\Contracts\LocationRepository;
use Platform\Holidays\Transformers\LocationTransformer;
use Platform\Holidays\Transformers\MetaLocationTransformer;
use Platform\Holidays\Transformers\MinimalLocationTransformer;
use Platform\Holidays\Validators\LocationValidator;

class LocationController extends ApiController
{
    protected $commandBus;

    protected $locationRepository;

    protected $locationValidator;

    public function __construct(
        DefaultCommandBus $commandBus,
        LocationRepository $locationRepository,
        LocationValidator $locationValidator
    ){
        $this->commandBus = $commandBus;
        $this->locationRepository = $locationRepository;
        $this->locationValidator = $locationValidator;

        parent::__construct(new Manager());
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $locationList = $this->locationRepository->getAll();
        if (isset($data['dropDown']) && $data['dropDown'] == true) {
            return $this->respondWithCollection($locationList, new MinimalLocationTransformer, 'Location List');
        }
        return $this->respondWithCollection($locationList, new MetaLocationTransformer, 'Location List');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->locationValidator->setCreateRules()->validate($request->all());
        $location = $this->commandBus->execute(new AddLocationCommand($request->all()));

        if($location) 
            return $this->respondWithNewItem($location, new LocationTransformer, 'Location');
        else
            return $this->respondWithError('Something went wrong. Please try again', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location = $this->locationRepository->getById($id);

        if($location)
            return $this->respondWithItem($location, new LocationTransformer, 'Location item');
        else
            return $this->respondWithError('Location not found', 500);
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
        $this->locationValidator->setUpdateRules()->validate($request->all());
        $location = $this->commandBus->execute(new UpdateLocationCommand($request->all(), $id));

        if($location) 
            return $this->respondOk('Updated successfully');
            //return $this->respondWithItem($location, new LocationTransformer, 'Holiday');
        else
            return $this->respondWithError('Something went wrong. Please try again', 500);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->commandBus->execute(new DeleteLocationCommand($id));

        if($result) 
            return $this->respondOk('Location deleted successfully');
        else
            return $this->respondWithError('Something went wrong. Please try again', 500);
    }
}
