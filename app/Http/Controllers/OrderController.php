<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Helpers\Helpers;
use Platform\Customer\Repositories\Contracts\CustomerRepository;
use Platform\Orders\Commands\AllOrderListCommand;
use Platform\Orders\Commands\CreateOrderCommand;
use Platform\Orders\Commands\DeleteOrderCommand;
use Platform\Orders\Commands\ShowOrderByIdCommand;
use Platform\Orders\Commands\UpdateOrderCommand;
use Platform\Orders\Helpers\OrderHelpers;
use Platform\Orders\Repositories\Contracts\OrderRepository;
use Platform\Orders\Transformers\OrderTransformer;
use Platform\Orders\Validators\Orders;

class OrderController extends ApiController
{
    /**
     * For Calling Commands
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * For Shipment Validator
     * @var Platform\Orders\Validators\Orders
     */
    protected $order;

    /**
     * @var Platform\Orders\Repositories\Contracts\OrderRepository
     */
    protected $orderRepo;

    /**
     * @var Platform\Customer\Repositories\Contracts\CustomerRepository
     */
    protected $customerRepo;

    /**
     * @param Platform\App\Commanding\DefaultCommandBus  $commandBus
     * @param Platform\Orders\Repositories\Contracts\OrderRepository    $orderRepo
     * @param Platform\Customer\Repositories\Contracts\CustomerRepository $customerRepo
     * @param Platform\Orders\Validators\Orders             $order
     */
    public function __construct(DefaultCommandBus $commandBus,
                                OrderRepository $orderRepo,
                                CustomerRepository $customerRepo,
                                Orders $order)
    {
        $this->commandBus = $commandBus;
        $this->order = $order;
        $this->customerRepo = $customerRepo;
        $this->orderRepo = $orderRepo;
        parent::__construct(new Manager());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $command=new AllOrderListCommand($request);
        $response=$this->commandBus->execute($command);

        return $this->respondWithPaginatedCollection($response, new OrderTransformer, 'orders');
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
        $command = new CreateOrderCommand($formData);
        $response = $this->commandBus->execute($command);

        return $this->respondWithNewItem($response, new OrderTransformer, 'order');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $command=new ShowOrderByIdCommand($id);
        $response=$this->commandBus->execute($command);

        return $this->respondWithItem($response, new OrderTransformer, 'order');
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
        $command = new UpdateOrderCommand($formData, $id);
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
        $command = new DeleteOrderCommand($id);
        $response = $this->commandBus->execute($command);

        return $this->respondOk($response);
    }

    /**
     * @param  $customerId
     * @return orders
     */
    public function search($customerId)
    {
        $orders = $this->orderRepo->searchOrder($customerId);
        return $this->respondWithCollection($orders, new OrderTransformer, 'orders');
    }

    /**
     * @param  Illuminate\Http\Request $request
     * @return mixed
     */
    public function filter(Request $request)
    {
        $orders = $this->orderRepo->filterOrder($request->all());
        return $this->respondWithPaginatedCollection($orders , new OrderTransformer, 'orders');
    }
}
