<?php

namespace Platform\Line\Transformers;

use App\Customer;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Customer\Transformers\MetaCustomerTransformer;
use Platform\Users\Transformers\MetaUserTransformer;
use app\User;

class MetaLineTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($line)
	{
            $customer = [
                'customerId' =>  $line->customer->id,
                'code' => $line->customer->code,
                'name' => $line->customer->name,
            ];

        if ($line->salesRepresentative) {
            $salesRepresentative = (new MetaUserTransformer())->transform($line->salesRepresentative);
        } else {
            $salesRepresentative = null;
        }

            return [
                'id' => $line->id,
                'customer' => $customer,
                'code' => $line->code,
                'name' => $line->name,
                'salesRepresentative' => $salesRepresentative,
                'soTargetDate' => $line->so_target_date->toDateTimeString(),
                'deliveryTargetDate' => $line->delivery_target_date->toDateTimeString(),
                'stylesCount' => intval($line->styles_count),
                'createdAt' => $line->created_at->toDateTimeString(),
                'updatedAt' => $line->updated_at->toDateTimeString(),
                'archivedAt' => is_null($line->archived_at)? NULL : $line->archived_at->toDateTimeString(),
                'completedAt' => is_null($line->completed_at)? NULL : $line->completed_at->toDateTimeString(),
                
            ];
	}

}
