<?php

namespace Platform\Pom\Repositories\Eloquent;

use App\Size;
use Platform\App\Exceptions\SeException;
use Platform\App\Helpers\Helpers;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Pom\Repositories\Contracts\SizeRepository;

class EloquentSizeRepository extends Repository implements SizeRepository 
{

	public function model(){
		return 'App\Size';
	}

	/**
	 * @param  string $type 
	 * @return collection
	 */
	public function getAllSize($type)
    {
    	$sizeType = \App\SizeType::where('size_type',  strtoupper($type))->first();

    	if ($sizeType) {
        	return $this->model->where('size_type_id', $sizeType->id)->get();
    	}
    	return $this->model->all();
    }

    /**
     * Add a new size 
     *
     * @param mixed $data
     * @return mixed
     */
    public function addSize($data) 
    {
    	$size = $this->model
    		->where('size', strtoupper($data['size']))
    		->first();
    	if ($size) {
    		throw new SeException("Size  already exists", 400, 4000890);
    	}

        $data = [
            'size' => strtoupper($data['size']),
            'code' => $this->generateCategoryCode(),
            'size_type_id' => $data['sizeTypeId']
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
    public function updateSize($code, $data) 
    {
    	$size = $this->model
    		->where('size', strtoupper($data['size']))
    		->first();
    	if ($size) {
    		throw new SeException("size already exists", 400, 4000890);
    	}
    	
        $data = [
            'size' => strtoupper($data['size']),
        ];
        $updated = $this->model->update($data, $code, 'code');

        if ($updated) {
             return $this->model->where('code', $code)->first();
        }
        return $updated;
    }

    /**
     * Delete size 
     * 
     * @param  string $code 
     * @return boolean
     */
    public function deleteSize($code)
    {
    	return $this->model->where('code', $code)->delete();
    }

    /**
     * Generates size code
     *
     * @return string
     */
    public function generateCategoryCode() 
    {
        $size = $this->model->orderBy('code', 'desc')->get();
		if (!empty($size)) {
		    if ($size[0]->code == 99) {
		        $code = str_pad($size[1]->code + 1, 2, '0', STR_PAD_LEFT);
		    } else {
		        $code = str_pad($size[0]->code + 1, 2, '0', STR_PAD_LEFT);
		    }        
		    return $code;
		}
		return '00';
    }   
}