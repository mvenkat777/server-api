<?php

namespace Platform\Holidays\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Holidays\Repositories\Contracts\HolidayRepository;
use App\Holiday;

class EloquentHolidayRepository extends Repository implements HolidayRepository 
{

	public function model(){
		return 'Platform\Holidays\Models\Holiday';
	}

    /**
     * Get all holidays 
     */
    public function getAll()
    {
        $holiday = \DB::SELECT(
            "SELECT DISTINCT ON (h.date, l.country) h.*, l.* FROM holidays h,
            locations l where l.id = h.location_id    
            " 
        );
        return $holiday;
    }

    /**
     * Get holidays list by location id
     *
     * @param string $locationId
     * @return Collection
     */
    public function getByLocation($locationId)
    {
        return $this->model->where('location_id', $locationId)->get();
    }

    /**
     * Get holidays list by year and locationId
     *
     * @param string $locationId
     * @param integer $year
     * @return Collection
     */
    public function getByYearAndLocation($locationId, $year)
    {
        return $this->model->where('location_id', $locationId)
                            ->where('year', $year)
                            ->get();
    }

    /**
     * Get holiday list of an specific user
     *
     * @param string $userId
     * @return Collection
     */
    public function getByUserId($userId)
    {
        $userLocation = \App\UserDetail::where('user_id', $userId)
                                        ->select('location')
                                        ->first();
        if(is_null($userLocation->location)) {
            return [];
        }
        return $this->getByLocation(json_decode($userLocation->location)->id);
    }

    /**
     * Get holiday by id
     *
     * @param string $holidayId
     * @return Model
     */
    public function getById($holidayId)
    {
        return $this->model->find($holidayId);
    }

    /**
     * Store holiday to a location
     *
     * @param array $data
     * @return Model
     */
    public function add($data)
    {
        $dbData = [
            'id' => $this->generateUUID(),
            'date' => $data['date'],
            'day' => $data['weekDay'],
            'is_work_day' => $data['isWorkDay'],
            'affected_supply_chain' => $data['affectedSupplyChain'],
            'location_id' => $data['locationId'],
            'description' => $data['description'],
            'year' => $data['year']
        ];

        return $this->create($dbData);
    }

    /**
     * Update holiday
     *
     * @param array $data
     * @param string $holidayId
     * @return integer
     */
    public function updateHoliday($data, $holidayId)
    {
        $dbData = [
            'date' => $data['date'],
            'day' => $data['weekDay'],
            'is_work_day' => $data['isWorkDay'],
            'affected_supply_chain' => $data['affectedSupplyChain'],
            'description' => $data['description']
        ];

        return $this->update($dbData, $holidayId);
    }

    /**
     * Delete holiday 
     *
     * @param string $holidayId
     */
    public function deleteHoliday($holidayId)
    {
        return $this->delete($holidayId);
    }

    /**
     * Delete holidays by location id
     *
     * @param string $locationId
     * @return integer [no of rows deleted]
     */
    public function deleteByLocationId($locationId)
    {
        return $this->model->where('location_id', $locationId)->delete();
    }

}
