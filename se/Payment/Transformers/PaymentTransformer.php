<?php

namespace Platform\Payment\Transformers;

use League\Fractal\TransformerAbstract;
use App\Payment;

class PaymentTransformer extends TransformerAbstract
{
    public function transform(Payment $payment)
    {
        return [
            'id' => $payment->id,
            'email' => $payment->email,
            'name' => $payment->name,
            'productName' => $payment->product_name,
            'description' => $payment->description,
            'amount' => $payment->amount,
            'userObject' => json_decode($payment->user_object),
            'userLocation' => $payment->userLocation,
            'productLink' => $payment->product_link,
            'status' => $payment->status,
            'creatorName'=>$payment->sender_name,
            'creatorEmail'=>$payment->sender_email,
            'uploadLinkObject' => json_decode($payment->upload_link_object),
            'createdAt' => $payment->created_at->toDateTimeString(),
            'updatedAt' => $payment->updated_at->toDateTimeString()
        ];
    }

}
