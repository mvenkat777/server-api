<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Helpers\Helpers;
use Platform\Shipments\Commands\AllShipmentListCommand;
use Platform\Shipments\Commands\CreateShipmentCommand;
use Platform\Shipments\Commands\DeleteShipmentCommand;
use Platform\Shipments\Commands\ShowShipmentByIdCommand;
use Platform\Shipments\Commands\UpdateShipmentCommand;
use Platform\Shipments\Helpers\ShipmentHelpers;
use Platform\Shipments\Repositories\Contracts\ShipmentRepository;
use Platform\Shipments\Transformers\ShipmentTransformer;
use Platform\Shipments\Validators\Shipments;

class ShipmentController extends ApiController
{

    /**
     * For Calling Commands
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * For Shipment Validator
     * @var Platform\Shipments\Validators\Shipments
     */
    protected $shipment;

    /**
     * @var Platform\Shipments\Repositories\Contracts\ShipmentRepository
     */
    protected $shipmentRepo;

    /**
     * @param DefaultCommandBus
     * @param Shipments
     */
    public function __construct(DefaultCommandBus $commandBus,
        Shipments $shipment,
        ShipmentRepository $shipmentRepo
    ){
        $this->commandBus = $commandBus;
        $this->shipment = $shipment;
        $this->shipmentRepo = $shipmentRepo;
        parent::__construct(new Manager());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $command=new AllShipmentListCommand($request);
        $response=$this->commandBus->execute($command);

        return $this->respondWithPaginatedCollection($response, new ShipmentTransformer, 'shipment');
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
        $formData =$request->all();
        $this->shipment->validate($formData);
        $command = new CreateShipmentCommand($formData);
        $response = $this->commandBus->execute($command);

        return $this->respondWithNewItem($response, new ShipmentTransformer, 'shipment');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $command=new ShowShipmentByIdCommand($id);
        $response=$this->commandBus->execute($command);

        return $this->respondWithItem($response, new ShipmentTransformer, 'shipment');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $formData = $request->all();
        $command=new UpdateShipmentCommand($formData, $id);
        $response=$this->commandBus->execute($command);

        return $this->respondOk($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $command = new DeleteShipmentCommand($id);
        $response = $this->commandBus->execute($command);

        return $this->respondOk($response);
    }

    public function filter(Request $request)
    {
        return $this->respondWithPaginatedCollection(
            $this->shipmentRepo->filterShipment($request->all()),
            new ShipmentTransformer,
            'shipments'
        );
    }
}
