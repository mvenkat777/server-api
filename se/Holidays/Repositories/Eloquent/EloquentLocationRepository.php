<?php

namespace Platform\Holidays\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Holidays\Repositories\Contracts\LocationRepository;
use App\Location;
use Platform\App\Exceptions\SeException;

class EloquentLocationRepository extends Repository implements LocationRepository 
{

	public function model(){
		return 'Platform\Holidays\Models\Location';
	}

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function add($data)
    {
        $dbData = [
            'id' => $this->generateUUID(),
            'city'=> $data['city'],
            'state'=> $data['state'],
            'country'=> $data['country'],
            'code'=> $data['code'],
            'postal_code'=> $data['postalCode'],
            'address'=> $data['address']
        ];

        try{
            return $this->create($dbData);
        } catch(\Exception $e) {
            throw new SeException('Something is not right. Please try again', 422, 4300100);
        }
    }

    public function updateLocation($data, $locationId)
    {
        $cods = $this->model->whereNot('id', $locationId)->lists('code');
        if (in_array($data['code'], $codes)) {
            throw new SeException("code already exists", 422, 4300101);
        }

        $dbData = [
            'city'=> $data['city'],
            'state'=> $data['state'],
            'country'=> $data['country'],
            'code'=> $data['code'],
            'postal_code'=> $data['postalCode'],
            'address'=> $data['address']
        ];

        try{
            return $this->update($dbData, $locationId);
        } catch(\Exception $e) {
            throw new SeException('Something is not right. Please try again', 422, 4300100);
        }
    }

    public function deleteLocation($locationId)
    {
        return $this->delete($locationId);
    }

}
