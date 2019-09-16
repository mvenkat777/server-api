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
use Platform\Line\Transformers\MetaLineTransformer;
use Platform\SampleContainer\Transformers\MetaSampleContainerTransformer;

class TNATransformer extends TransformerAbstract 
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($tna)
	{
		$order = $tna->order;
		if(!is_null($order)){
			$order = $this->item($order, new MetaOrderTransformer);
			$order = $this->manager->createData($order)->toArray();
		}
		else{
			$order['data'] = NULL;
		}

		$techpack = $tna->techpack;
		if(!is_null($techpack)){
			$techpack = $this->item($techpack, new MetaTechpackTransformer);
			$techpack = $this->manager->createData($techpack)->toArray();
            $techpack['data']['image'] = $tna->techpack->image;
		}
		else{
			$techpack['data'] = NULL;
		}

        $customer = $tna->customer;
        $customerArchivedAt = null;
        if($customer) {
            $customerArchivedAt = $customer->archived_at;
        }
		// $customer = $this->item($tna->customer, new CustomerTransformer);
		// $customer = $this->manager->createData($customer)->toArray();

		//$vendors = $this->collection($tna->vendors, new MetaVendorTransformer);
		//$vendors = $this->manager->createData($vendors)->toArray();
		
		$creator = $this->item($tna->creator, new MetaUserTransformer);
		$creator = $this->manager->createData($creator)->toArray();

		$representor = $this->item($tna->representor, new MetaUserTransformer);
		$representor = $this->manager->createData($representor)->toArray();

        $style = $tna->style();
        $line = null;
        $sampleContainer = null;
        if(!is_null($style)) {
            $line = (new MetaLineTransformer)->transform($style->line);

            if ($style->sampleContainer) {
                $sampleContainer = (new MetaSampleContainerTransformer())->transform($style->sampleContainer);
            }
        }

		return [
			'tnaId' => $tna->id,
			'title' => $tna->title,
			'order' => $order['data'],
			'techpack' => $techpack['data'],
			'customerId' => $tna->customer_id,
			//'vendors' => $vendors['data'],
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
			'customerCode' => $tna->customer_code,
            'customerArchivedAt' => $customerArchivedAt,
			'styleDescription' => $tna->style_description,
			'representor' => $representor['data'],
			'tnaHealth' => $tna->health->health,
			'attachment' => json_decode($tna->attachment),
			//'itemsOrder' => (new \Platform\TNA\Handlers\Console\ItemsOrderCalculator)->calculate($tna->id),
			'itemsOrder' => json_decode($tna->items_order),
            'isCreatingPreset' => $tna->is_creating_preset,
            'isPublishing' => $tna->is_publishing,
            'line' => $line,
            'sampleContainer' => $sampleContainer,
			'createdAt' => $tna->created_at->toDateTimeString(),
			'updatedAt' => $tna->updated_at->toDateTimeString(),
            'archivedAt' => $tna->archived_at
		];
	}

}
