<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Payment\Commands\DestroyPaymentOrderCommand;
use Platform\Payment\Commands\GetAllOrdersCommand;
use Platform\Payment\Commands\GetRequestStatusCommand;
use Platform\Payment\Commands\MakeNewPaymentCommand;
use Platform\Payment\Commands\SearchPaymentCommand;
use Platform\Payment\Commands\ShowPaymentLinkCommand;
use Platform\Payment\Repositories\Contracts\PaymentRepository;
use Platform\Payment\Transformers\PaymentTransformer;
use Platform\Payment\Transformers\PaymentTransformerById;
use Platform\Payment\Validators\Payments;

class PaymentController extends ApiController
{
    protected $commandBus;

    protected $payments;

    protected $paymentRepository;

    public function __construct(
        DefaultCommandBus $commandBus, 
        Payments $payments,
        PaymentRepository $paymentRepository
    ) {
        $this->commandBus = $commandBus;
        $this->payments =$payments;
        $this->paymentRepository =$paymentRepository;

        parent::__construct(new Manager());
    }
    /**
     * Display a listing of the resource link.
     *
     * @return \Illuminate\Http\Response
     */

    public function showLink($id)
    {
        $ipaddress = '';
        $details = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        if($ipaddress != 'UNKNOWN')
        {
            $userLocation = json_decode(file_get_contents("http://ipinfo.io/{$ipaddress}/json"));
        }
        $getOrder = $this->commandBus->execute(new ShowPaymentLinkCommand($id,$userLocation));
        if (method_exists($getOrder, 'getContent')) {
                return $getOrder;
        }
        if($getOrder){
            return $this->respondWithItem($getOrder, new PaymentTransformer, 'generalPayments');
       }
       return \Response::json([
                    'data' => $getOrder,
                    'status_code' => 200
                ], 200);
    }

    /**
     * Get the return status after transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStatus(Request $request, $id)
    {
        return $this->commandBus->execute(new GetRequestStatusCommand($request->status, $id, $request->paymentStatus));
    }

    /**
     * Get the return status after transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllOrders(Request $request)
    {
       $orders = $this->commandBus->execute(new GetAllOrdersCommand($request->items));
       if($orders){
            return $this->respondWithPaginatedCollection($orders, new PaymentTransformer, 'generalPayments');
       }
       return \Response::json([
                    'data' => $orders,
                    'status_code' => 200
                ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->payments->validate($request->all());
        $response = $this->commandBus->execute(new MakeNewPaymentCommand($request->all()));
        return $response;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->commandBus->execute(new DestroyPaymentOrderCommand($id));
    }

    /**
     * Get the search result.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $result = $this->paymentRepository->filterPayment($request->all());
        return $this->respondWithPaginatedCollection($result, new PaymentTransformer, 'generalPayments');
    }
}
