<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Holidays\Commands\AddHolidayCommand;
use Platform\Holidays\Commands\DeleteHolidayCommand;
use Platform\Holidays\Commands\UpdateHolidayCommand;
use Platform\Holidays\Repositories\Contracts\HolidayRepository;
use Platform\Holidays\Transformers\HolidayLocationTransformer;
use Platform\Holidays\Transformers\HolidayTransformer;
use Platform\Holidays\Transformers\MetaHolidayTransformer;
use Platform\Holidays\Validators\HolidayValidator;

class HolidayController extends ApiController
{
    protected $commandBus;

    protected $holidayRepository;

    protected $holidayValidator;

    public function __construct(
        DefaultCommandBus $commandBus,
        HolidayRepository $holidayRepository,
        HolidayValidator $holidayValidator 
    ){
        $this->commandBus = $commandBus;
        $this->holidayValidator = $holidayValidator;
        $this->holidayRepository = $holidayRepository;

        parent::__construct(new Manager());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $holidayList = $this->holidayRepository->getAll();
        return $this->respondWithCollection($holidayList, new HolidayLocationTransformer, 'Holiday List');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByLocation($locationId)
    {
        $holidayList = $this->holidayRepository->getByLocation($locationId);
        return $this->respondWithCollection($holidayList, new HolidayTransformer, 'Holiday List');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByYear($locationId, $year)
    {
        $holidayList = $this->holidayRepository->getByYearAndLocation($locationId, $year);
        return $this->respondWithCollection($holidayList, new MetaHolidayTransformer, 'Holiday List By Year');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getListOfUser($userId)
    {
        $holidayList = $this->holidayRepository->getByUserId($userId);
        return $this->respondWithCollection($holidayList, new MetaHolidayTransformer, 'Holiday List By Year');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $locationId)
    {
        $this->holidayValidator->setCreateRules()->validate($request->all());
        $holiday = $this->commandBus->execute(new AddHolidayCommand($request->all(), $locationId));

        if($holiday) 
            return $this->respondWithNewItem($holiday, new HolidayTransformer, 'Holiday');
        else
            return $this->respondWithError('Something went wrong. Please try again', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($locationId, $holidayId)
    {
        $holiday = $this->holidayRepository->getById($holidayId);
        return $this->respondWithItem($holiday, new HolidayTransformer, 'Holiday item');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $locationId, $holidayId)
    {
        $this->holidayValidator->setCreateRules()->validate($request->all());
        $holiday = $this->commandBus->execute(new UpdateHolidayCommand($request->all(), $holidayId));

        if($holiday) 
            return $this->respondOk("Updated successfully");
            //return $this->respondWithItem($holiday, new HolidayTransformer, 'Holiday');
        else
            return $this->respondWithError('Something went wrong. Please try again', 500);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($locationId, $holidayId)
    {
        $result = $this->commandBus->execute(new DeleteHolidayCommand($holidayId));

        if($result) 
            return $this->respondOk('Holiday deleted successfully');
        else
            return $this->respondWithError('Something went wrong. Please try again', 500);
    }
}
