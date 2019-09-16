<?php

namespace Platform\Pom\Repositories\Eloquent;

use App\Classification;
use Platform\App\Exceptions\SeException;
use Platform\App\Helpers\Helpers;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Pom\Repositories\Contracts\ClassificationRepository;

class EloquentClassificationRepository extends Repository implements ClassificationRepository 
{

	public function model(){
		return 'App\Classification';
	}

	public function getAllClassification()
    {
        return $this->model->orderBy('code')->get();
    }

    /**
     * Add a new classification
     *
     * @param mixed $data
     * @return mixed
     */
    public function addClassification($data) 
    {
    	$classification = $this->model
    		->where('classification', Helpers::toSnakecase($data['classification']))
    		->first();
    	if ($classification) {
    		throw new SeException("classification already exists", 400, 4000890);
    	}
        $data = [
            'code' => $this->generateCategoryCode(),
            'classification' => Helpers::toSnakecase($data['classification']),
        ];
        return $this->create($data);
    }    

    /**
     * Update a classification
     *
     * @param mixed $data
     * @return mixed
     */
    public function updateClassification($code, $data) 
    {
        $classification = $this->model
            ->where('classification', Helpers::toSnakecase($data['classification']))
            ->first();
        if ($classification) {
            throw new SeException("classification already exists", 400, 4000890);
        }
        $data = [
            'classification' => Helpers::toSnakecase($data['classification']),
        ];
        $updated = $this->update($data, $code, 'code');

        if ($updated) {
             return $this->model->where('code', $code)->first();
        }
        return $updated;
    }

    /**
     * Delete Classification 
     * 
     * @param  string $code 
     * @return boolean
     */
    public function deleteClassification($code)
    {
    	return $this->model->where('code', $code)->delete();
    }

    /**
     * Generates classification code
     *
     * @return string
     */
    public function generateCategoryCode() 
    {
        $classification = $this->model->orderBy('code', 'desc')->get();
		if (!empty($classification->toArray())) {
		    if ($classification[0]->code == 99) {
		        $code = str_pad($classification[1]->code + 1, 2, '0', STR_PAD_LEFT);
		    } else {
		        $code = str_pad($classification[0]->code + 1, 2, '0', STR_PAD_LEFT);
		    }        
		    return $code;
		}
		return '00';
    }   
}