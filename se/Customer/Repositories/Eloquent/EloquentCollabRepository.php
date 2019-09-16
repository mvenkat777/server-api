<?php

namespace Platform\Customer\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Customer\Repositories\Contracts\CollabRepository;
use Platform\Customer\Models\Collab;

class EloquentCollabRepository extends Repository implements CollabRepository
{
	public function model(){
		return 'Platform\Customer\Models\Collab';
	}


    public function addCollab($data, $customerId)
    {
        $data = [
            'id' => $this->generateUUID(),
            'customer_id' => $customerId,
            'sales_lead_id' => $data['salesLeadId'],
            'name' => $data['name'],
            'url' => $data['url'],
            'logo' => json_encode($data['logo']),
        ];
        return $this->create($data);
    }

    /**
     * Update an existing collab
     *
     * @param array $data
     * @param string $customerId
     */
    public function updateCollab($data, $customerId)
    {
        $data = [
            'sales_lead_id' => $data['salesLeadId'],
            'logo' => json_encode($data['logo']),
        ];
        $collab = $this->getByCustomerId($customerId);
        return $collab->update($data);
    }

    public function getByCustomerIdWithRelations($customerId)
    {
        return $this->model->with('salesLead')
                           ->where('customer_id', $customerId)
                           ->first();
    }

    public function getUsersByCustomerId($customerId)
    {
        return $this->model->where('customer_id', $customerId)
                           ->first()
                           ->users()
                           ->get() ;
    }

    /**
     * Get collab by customer id
     *
     * @param string $customerId
     */
    public function getByCustomerId($customerId)
    {
        return $this->model->where('customer_id', $customerId)
                           ->first();
    }

    /**
     * Get a collab based on collab url
     *
     * @param string $collabUrl
     */
    public function getByUrl($collabUrl)
    {
        return $this->model->where('url', $collabUrl)
                           ->first();
    }
}
