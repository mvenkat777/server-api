<?php

namespace Platform\Customer\Transformers;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Customer\Transformers\MetaCustomerTransformer;
use Platform\Users\Transformers\MetaUserTransformer;

class CollabTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($collab)
	{
        $data = [
            'id' => $collab->id,
            'url' => $collab->url,
            'name' => $collab->name,
            'logo' => json_decode($collab->logo),
        ];

        if (isset($collab->salesLead)) {
            $salesLead = new Item($collab->salesLead, new MetaUserTransformer());
            $data['salesLead'] = $this->manager->createData($salesLead)->toArray()['data'];
        } else {
            $data['salesLead'] = null;
        }

        if (isset($collab->customer)) {
            $customer = new Item($collab->customer, new MetaCustomerTransformer());
            $data['customer'] = $this->manager->createData($customer)->toArray()['data'];
        } else {
            $data['customer'] = null;
        }
        return $data;
	}

}
