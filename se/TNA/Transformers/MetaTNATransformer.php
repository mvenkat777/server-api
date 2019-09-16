<?php

namespace Platform\TNA\Transformers;

use Carbon\Carbon;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Customer\Transformers\CustomerTransformer;
use Platform\Orders\Transformers\MetaOrderTransformer;
use Platform\Techpacks\Transformers\MetaTechpackTransformer;
use Platform\Users\Transformers\MetaUserTransformer;
use Platform\Vendor\Transformers\MetaVendorTransformer;

class MetaTNATransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($tna)
	{
		// $customer = $this->item($tna->customer, new CustomerTransformer);
		// $customer = $this->manager->createData($customer)->toArray();

		//$vendors = $this->collection($tna->vendors, new MetaVendorTransformer);
		//$vendors = $this->manager->createData($vendors)->toArray();
		
		$creator = $this->item($tna->creator, new MetaUserTransformer);
		$creator = $this->manager->createData($creator)->toArray();

		$representor = $this->item($tna->representor, new MetaUserTransformer);
		$representor = $this->manager->createData($representor)->toArray();

		return [
			'tnaId' => $tna->id,
			'title' => $tna->title,
			'customerId' => $tna->customer_id,
			'creator' => $creator['data'],
			'state' => $tna->state->state,
			'startDate' => is_object($tna->start_date) 
								? $tna->start_date->toDateTimeString() 
								: $tna->start_date, 
			'targetDate' => is_object($tna->target_date)
								? $tna->target_date->toDateTimeString()
								: $tna->target_date,
			'isPublished' => $tna->is_published,
			'publishedDate' => !is_null($tna->published_date)
									? Carbon::parse($tna->published_date)->toDateTimeString()
									: NULL,
			'projectedDate' => !is_null($tna->projected_date)
									? Carbon::parse($tna->projected_date)->toDateTimeString()
									: NULL,
			'completedDate' => !is_null($tna->completed_date)
									? Carbon::parse($tna->completed_date)->toDateTimeString()
									: NULL,
			'customerName' => $tna->customer_name,
			// 'customerCode' => $tna->customer_code,
			// 'styleDescription' => $tna->style_description,
			'representor' => $representor['data'],
			'tnaHealth' => $tna->health->health,
            // 'isCreatingPreset' => $tna->is_creating_preset,
            // 'isPublishing' => $tna->is_publishing,
			'createdAt' => $tna->created_at->toDateTimeString(),
            'updatedAt' => $tna->updated_at->toDateTimeString(),
            'archivedAt' => $tna->archived_at
		];
	}

}
