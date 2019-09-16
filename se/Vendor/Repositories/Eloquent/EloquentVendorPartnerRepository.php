<?php

namespace Platform\Vendor\Repositories\Eloquent;

use App\VendorPartner;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\App\Repositories\Eloquent\generateUUID;
use Platform\Vendor\Repositories\Contracts\VendorPartnerRepository;

class EloquentVendorPartnerRepository extends Repository implements VendorPartnerRepository 
{

	public function model(){
		return 'App\VendorPartner';
	}

    /**
     * @param array $data       
     * @param string $VendorId 
     */
	public function addPartner($data, $vendorId)
	{
		$partner = [
				'id' => $this->generateUUID(),
				'vendor_id' => $vendorId,	
				'name' => $data['name'],
				'role' => $data['role']
		];
		return $this->create($partner);
	}

    /**
     * @param array $data       
     * @param string $vendorId 
     */
    public function updatePartner($data, $vendorId)
    {
        $partner = [
                'id' => $data['id'],
                'vendor_id' => $vendorId,   
                'name' => $data['name'],
                'role' => $data['role']
        ];
        $resp = $this->model->where('id', '=', $data['id'])
                    ->where('vendor_id', '=', $vendorId)
                    ->first();
                    
        if ($resp) {
            $resp->update($partner);
        }   
    }

	/**
     * @param string $vendorId 
     * @param int $contact_id  
     */
    public function addContact($partner_id, $contact_id)
    {
        $this->model->find($partner_id)->contact()->sync([$contact_id]);
    }

    /**
     * @param string $vendorId 
     * @param int $address_id  
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
    	$resp = $this->model->whereIn('id', $partner)->first();
        if ($resp) {
            $resp->delete();
        }
    }

    public function delete($partner)
    {
        $part = $this->model->where('id', '=', $partner)->with(['contact'])->first();
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