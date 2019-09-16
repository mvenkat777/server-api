<?php

namespace Platform\Pom\Repositories\Eloquent;

use App\SizeRange;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Pom\Repositories\Contracts\SizeRangeRepository;

class EloquentSizeRangeRepository extends Repository implements SizeRangeRepository 
{

	public function model(){
		return 'App\SizeRange';
	}

	/**
	 * @param  string $type 
	 * @return collection
	 */
	public function getAllSizeRange($type)
    {
    	// $sizeType = \App\SizeType::where('size_type',  strtoupper($type))->first();
    	if ($type) {
        	return $this->model->where('size_type_id', $type)->orderBy('code', 'asc')->get();
    	}
        return $this->model->orderBy('code', 'asc')->get();
    }

    /**
     * Add a new sizeRange 
     *
     * @param mixed $data
     * @return mixed
     */
    public function addSizeRange($data) 
    {
    	$sizeRange = $this->model
    		->where('range', $this->getRangeNameByRangeValue($data['value']))
    		->first();
    	if ($sizeRange) {
    		throw new SeException("Size Range  already exists", 400, 4000890);
    	}

        $data = [
            'range' => $this->getRangeNameByRangeValue($data['value']),
            'code' => $this->generateCategoryCode(),
            'range_value' => json_encode(array_map('strtoupper',$data['value'])),
            'size_type_id' => $data['sizeTypeId']
        ];
        return $this->create($data);
    }   

    public function getRangeNameByRangeValue($rangeValue)
    {
        return strtoupper($rangeValue[0].'-'.$rangeValue[count($rangeValue)-1]);
    }

    /**
     * Update a sizeRange type
     *
     * @param integer $id
     * @param mixed $data
     * @return mixed
     */
    public function updateSizeRange($code, $data) 
    {
    	$sizeRange = $this->model
    		->where('range', $this->getRangeNameByRangeValue($data['value']))
            ->where('code', '!=', $code)
    		->first();
    	if ($sizeRange) {
    		throw new SeException("sizeRange already exists", 400, 4000890);
    	}
    	
        $data = [
            'range' => $this->getRangeNameByRangeValue($data['value']),
            'range_value' => json_encode(array_map('strtoupper',$data['value'])),
            'size_type_id' => $data['sizeTypeId']
        ];
        $updated = $this->update($data, $code, 'code');

        if ($updated) {
             return $this->model->where('code', $code)->first();
        }
        return $updated;
    }

    /**
     * Get size range by range
     * @param  string $range 
     * @return array
     */
    public function getBySizeRange($range)
    {
    	return $this->model->where('range', strtoupper($range))->first();
    }

    /**
     * Delete sizeRange 
     * 
     * @param  string $code 
     * @return boolean
     */
    public function deleteSizeRange($code)
    {
    	return $this->model->where('code', $code)->delete();
    }

    /**
     * Generates sizeRange code
     *
     * @return string
     */
    public function generateCategoryCode() 
    {
        $sizeRange = $this->model->orderBy('code', 'desc')->get();
		if (!empty($sizeRange->toArray())) {
		    if ($sizeRange[0]->code == 999) {
		        $code = str_pad($sizeRange[1]->code + 1, 3, '0', STR_PAD_LEFT);
		    } else {
		        $code = str_pad($sizeRange[0]->code + 1, 3, '0', STR_PAD_LEFT);
		    }        
		    return $code;
		}
		return '001';
    }   

}