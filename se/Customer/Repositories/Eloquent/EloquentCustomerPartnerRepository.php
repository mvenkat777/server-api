<?php

namespace Platform\Customer\Repositories\Eloquent;

use App\CustomerPartner;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\App\Repositories\Eloquent\generateUUID;
use Platform\Customer\Repositories\Contracts\CustomerPartnerRepository;

class EloquentCustomerPartnerRepository extends Repository implements CustomerPartnerRepository 
{

	public function model(){
		return 'App\CustomerPartner';
	}

    /**
     * @param array $data       
     * @param string $customerId 
     */
	public function addPartner($data, $customerId)
	{
		$partner = [
				'id' => $this->generateUUID(),
				'customer_id' => $customerId,	
				'name' => $data['name'],
				'role' => $data['role']
		];
		return $this->create($partner);
	}

    /**
     * @param array $data       
     * @param string $customerId 
     */
    public function updatePartner($data, $customerId)
    {
        $partner = [
                'id' => $data['id'],
                'customer_id' => $customerId,   
                'name' => $data['name'],
                'role' => $data['role']
        ];
        $partner1 = $this->model->where('id', '=', $data['id'])
                    ->where('customer_id', '=', $customerId)
                    ->first();
        if ($partner1) {
            return $partner1->update($partner);
        }
    }

	/**
     * @param string $customer_id [description]
     * @param int $contact_id  [description]
     */
    public function addContact($partner_id, $contact_id)
    {
        $this->model->find($partner_id)->contact()->sync([$contact_id]);
    }

    /**
     * @param string $customer_id [description]
     * @param int $address_id  [description]
     */
    public function addAddress($partner_id, $address_id)
    {
        $this->model->find($partner_id)->address()->sync([$address_id]);
    }

    public function deletePartner($partner)
    {
    	foreach ($partner as  $value) {
    		$part = $this->model->where('id', '=', $value)->first();
    		$contacts = $part->contact;
    		foreach ($contacts as $contact) {
    			$contact->delete();
    		}
    		$part->contact()->detach();
    	}
    	$partner1 = $this->model->whereIn('id', $partner)->first();
        return $partner1->delete();
    }

    public function delete($partner)
    {
        $part = $this->model->where('id', '=', $partner)->with(['contact','address'])->first();
        if($part){
            $contacts = $part->contact;
            foreach ($contacts as $contact) {
                $contact->delete();
            }
            $part->contact()->detach();

            $addresses = $part->address;
            foreach ($addresses as $address) {
                $address->delete();
            }
            $part->address()->detach();
            return $part->delete();
        }
        return 0;
    }
}