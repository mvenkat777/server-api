<?php

namespace Platform\Customer\Transformers;

use App\CustomerPartner;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Address\Transformers\AddressTransformer;
use Platform\Contacts\Transformers\ContactTransformer;

class PartnerTransformer extends TransformerAbstract
{
	public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(CustomerPartner $partner)
    {
        $contact = $this->collection($partner->contact, new ContactTransformer);
        $contact = $this->manager->createData($contact)->toArray();

        $address = $this->collection($partner->address, new AddressTransformer);
        $address = $this->manager->createData($address)->toArray();

        return [
            'id' => (string)$partner->id,
            'customerId' => (string)$partner->customer_id,
            'name' => (string)$partner->name,
            'role' => (string)$partner->role,
            'address' => $address['data'],
            'contact' => $contact['data']
        ];
    }
}
