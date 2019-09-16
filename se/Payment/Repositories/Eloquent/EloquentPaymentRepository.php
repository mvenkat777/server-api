<?php

namespace Platform\Payment\Repositories\Eloquent;

use Illuminate\Support\Facades\Hash;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Payment\Commands\MakeNewPaymentCommand;
use Platform\Payment\Repositories\Contracts\PaymentRepository;
use App\Payment;

class EloquentPaymentRepository extends Repository implements PaymentRepository
{
    public function model()
    {
        return 'App\Payment';
    }
    /**
     * @param MakeNewPaymentCommand $command
     * @return mixed
     */

    public function makePayment(MakeNewPaymentCommand $command , $userObj)
    {
        $link = $this->generateUUID();
        if($command->uploadLinkObject == NULL || $command->uploadLinkObject == ' '){
            $payment = [
                'id' => $this->generateUUID(),
                'email' => $command->email,
                'name' => $command->name,
                'product_name' => $command->productName,
                'description'  => $command->description,
                'amount' => $command->amount,
                'product_link'   => 'http://payments.sourceeasy.com/?paymentId='.$link ,
                'payment_status' => 0,
                'sender_name' => \Auth::user()->display_name,
                'sender_email' => \Auth::user()->email,
                'upload_link_object' =>NULL,
                'user_object' => $userObj
            ];
        }
        else{
            $userObj = json_encode((array)json_decode($userObj));
            $payment = [
                'id' => $this->generateUUID(),
                'email' => $command->email,
                'name' => $command->name,
                'product_name' => $command->productName,
                'description'  => $command->description,
                'amount' => $command->amount,
                'product_link'   => 'http://payments.sourceeasy.com/?paymentId='.$link ,
                'payment_status' => 0,
                'sender_name' => \Auth::user()->display_name,
                'sender_email' => \Auth::user()->email,
                'upload_link_object' =>json_encode($command->uploadLinkObject),
                'user_object' => $userObj
            ];
        }
        return $this->model->create($payment);

    }
    /**
     * @param showLinkContent $command
     * @return all
     */

    public function showLinkContent($command){
        if(is_null($this->model->where('product_link','=',$command->link)->first()))
        {
            return \Response::json([
                'message' => "Unauthorized Link",
                'status_code' => 401
        ], 401);
        }
        else{
            $this->model->where('product_link','=',$command->link)->update(array('user_location' => json_encode(($command->userLocation))));
            return $this->model->where('product_link','=',$command->link)->first();
        }
    }

    /**
     * @param getRequestedStatus $command
     * @return 1
     */
    public function getAllOrder($command)
    {
        return $this->model->orderBy('updated_at', 'desc')->paginate($command->items);
    }
    /**
     * @param getRequestedStatus $command
     * @return 1
     */

    public function getRequestedStatus($command){
        if(is_null($this->model->where('product_link','=','http://payments.sourceeasy.com/?paymentId='.$command->id)->first()))
            {
                return \Response::json([
                    'message' => "Unauthorized Product Link",
                    'status_code' => 401
                ], 401);
            }
            if($command->paymentStatus == '1')
            {
                $this->model->where('product_link','=','http://payments.sourceeasy.com/?paymentId='.$command->id)
                     ->first()
                     ->update(['payment_status'=>'1','status'=> $command->status]);

                return \Response::json([
                    'data' => $this->model->where('product_link','=','http://payments.sourceeasy.com/?paymentId='.$command->id)->first(),
                    'status_code' => 200
                ], 200);
            }

            elseif($command->paymentStatus == '0')
            {
                $this->model->where('product_link','=','http://payments.sourceeasy.com/?paymentId='.$command->id)
                     ->first()
                     ->update(['payment_status'=>'0','status'=> $command->status]);

                return \Response::json([
                    'data' => $this->model->where('product_link','=','http://payments.sourceeasy.com/?paymentId='.$command->id)->first(),
                    'status_code' => 200
                ], 200);
            }
           else{
                return \Response::json([
                    'message' => "Payment Status is not valid.",
                    'status_code' => 401
                ], 401);
            }
    }
    /**
     * @param deletePaymentOrder $command
     * @return 1
     */

    public function deletePaymentOrder($command)
    {
        $payment = $this->model->where('product_link','=','http://payments.sourceeasy.com/?paymentId='.$command->id)->first();
        if(is_null($payment))
        {
            return \Response::json([
                    'message' => "Unauthorized Value",
                    'status_code' => 401
                ], 401);
        }
        else {
            $payment->SoftDeletes();
            return \Response::json([
                    'data' => "Successfully Deleted",
                    'status_code' => 200
                ], 200);
        }
    }

    /**
     * @param  array $data
     * @return mixed
     */
    public function filterPayment($data)
    {
        $item = isset($data['item'])? $data['item'] : config('constants.listItemLimit');
        return $this->filter($data)->paginate($item);
    }
}
