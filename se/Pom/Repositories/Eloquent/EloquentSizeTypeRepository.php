<?php

namespace Platform\Pom\Repositories\Eloquent;

use App\SizeType;
use Platform\App\Exceptions\SeException;
use Platform\App\Helpers\Helpers;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Pom\Repositories\Contracts\SizeTypeRepository;

class EloquentSizeTypeRepository extends Repository implements SizeTypeRepository 
{

	public function model(){
		return 'App\SizeType';
	}

	public function getAllSizeType()
    {
        return $this->model->orderBy('id')->get();
    }

    public function getSizeTypeByName($sizeType)
    {
        return $this->model->where('size_type', strtoupper($sizeType))->first();
    }

    /**
     * Add a new size type
     *
     * @param mixed $data
     * @return mixed
     */
    public function addSizeType($data) 
    {
    	$sizeType = $this->model
    		->where('size_type', strtoupper($data['sizeType']))
    		->first();
    	if ($sizeType) {
    		throw new SeException("Size Type already exists", 400, 4000890);
    	}

        $data = [
            'size_type' => strtoupper($data['sizeType']),
        ];
        return $this->create($data);
    }    

    /**
     * Update a size type
     *
     * @param integer $id
     * @param mixed $data
     * @return mixed
     */
    public function updateSizeType($id, $data) 
    {
    	$sizeType = $this->model
    		->where('size_type', strtoupper($data['sizeType']))
    		->first();
    	if ($sizeType) {
    		throw new SeException("sizeType already exists", 400, 4000890);
    	}
    	
        $data = [
            'size_type' => strtoupper($data['sizeType']),
        ];
        $updated = $this->update($data, $id, 'id');

        if ($updated) {
             return $this->model->where('id', $id)->first();
        }
        return $updated;
    }

    /**
     * Delete Size Type 
     * 
     * @param  integer $id 
     * @return boolean
     */
    public function deleteSizeType($id)
    {
    	return $this->model->where('id', $id)->delete();
    }
}