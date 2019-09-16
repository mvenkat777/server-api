<?php

namespace Platform\Pom\Repositories\Eloquent;

use App\Pom;
use Carbon\Carbon;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Pom\Repositories\Contracts\PomRepository;

class EloquentPomRepository extends Repository implements PomRepository 
{

	public function model(){
		return 'App\Pom';
	}

	/**
	 * get all pom
	 * 									
	 * @return mixed
	 */
	public function getAllPom($data)
    {
        if (isset($data['type']) && $data['type'] == 'archived') {
            return $this->model->orderBy('updated_at', 'desc')
                ->whereNotNull('archived_at')->get();
        }
        return $this->model->orderBy('updated_at', 'desc')
            ->whereNull('archived_at')->get();
    }
    
    public function getByRelatedCodes($categoryCode, $productTypeCode, $sizeTypeId)
    {
        return $this->model->where('category_code', $categoryCode)
            ->where('product_type_code', $productTypeCode)
            ->where('size_type_id', $sizeTypeId)
            ->first();
    }
    /**
     * Add a new classification
     *
     * @param mixed $data
     * @return mixed
     */
    public function addPom($data) 
    {
        $data = [
        	'id' => $this->generateUUID(),
        	'name' => $this->generateName($data),
            'category_code' => $data['categoryCode'],
            'product_type_code' => $data['productTypeCode'],
            'size_range_name' => $data['sizeRangeName'],
            'size_range_value' => json_encode($data['sizeRangeValue']),
            'size_type_id' => $data['sizeTypeId'],
            'base_size' => $data['baseSize'],
        ];
    	$pom = $this->model
    		->where('name', $data['name'])
    		->first();
    	if ($pom) {
    		throw new SeException("pom already exists", 400, 4000890);
    	}
    	\DB::beginTransaction();
    	$pom = $this->create($data);
    	\DB::commit();
        return $pom;
    }    

    /**
     * update the range the value
     * @param  array $data  
     * @param  string $pomId 
     * @return boolean        
     */     
    public function updatePom($data, $pomId)
    {
        $preRangeValue = json_decode($this->model->where('id', $pomId)->first()->size_range_value);
        $arrangedRangeValue = $this->arrangRangValue($data, $preRangeValue);
        $data = [
            'size_range_value' => json_encode($arrangedRangeValue),
            'size_range_name' => $this->createRangeName($arrangedRangeValue)
        ];

        $update = $this->model->where('id', $pomId)->update($data);
        return $this->model->find($pomId);
    }

    /**
     * Update a classification
     *
     * @param mixed $data
     * @return mixed
     */
    public function getPomById($id) 
    {
    	return $this->model->where('id', $id)->first();   
    }

    /**
     * Delete Classification 
     * 
     * @param  string $code 
     * @return boolean
     */
    public function deletePom($id)
    {
    	return $this->model->where('id', $id)->delete();
    }

    /**
     * archive pom
     * @param  integer $id 
     * @return boolean     
     */
    public function archivePom($id)
    {
        $pom = $this->model->where('id', $id)->first();
        $archivePom = $this->model->where('id', $id)
            ->update(['archived_at' => Carbon::now()]);
        if ($archivePom) {
            foreach ($pom->pomSheet as $sheet) {
                \DB::table('pom_sheets')->where('id', $sheet->id)
                    ->update(['archived_at' => Carbon::now()]);
            }
        }
        return $archivePom;
    }

    /**
     * rollback
     * @param  integer $id 
     * @return boolean     
     */
    public function rollback($id)
    {
        $pom = $this->model->where('id', $id)->first();
        $rollback = $pom->where('id', $id)->update(['archived_at' => NULL]);
        if ($rollback) {
            foreach ($pom->pomSheet as $sheet) {
                \DB::table('pom_sheets')->where('id', $sheet->id)
                    ->update(['archived_at' => NULL]);
            }
        }
        return $rollback;
    }

    /**
     * push element in array end or start
     * @param  araay $data  
     * @param  array $array 
     * @return array        
     */
    public function arrangRangValue($data, $array)
    {
        if (isset($data['position'])) {
            if ($data['position'] == 'end') {
                array_push($array, $data['value']);
                return $array;
            } elseif ($data['position'] == 'start') {
               array_unshift($array, $data['value']);
               return $array;
            }
        }
        throw new SeException("Please add the position", 422, 4220900);
        
    }

    /**
     * create rang name
     * @param  array $array 
     * @return string
     */
    public function createRangeName($array)
    {
        $rangeName = $array[0].'-'.end($array);
        return $rangeName;
    }

    /**
     * generate name for pom
     * @param  array $data 
     * @return mixed
     */
    public function generateName($data)
    {
        $category = \App\ProductCategory::where('code', $data['categoryCode'])->first()->category;
        $sizeType = \App\SizeType::where('id', $data['sizeTypeId'])->first()->size_type;
        $productType = \App\ProductType::where('code', $data['productTypeCode'])->first()->product_type;

        return $category.'-'.$productType.'-'.$sizeType.'-'.$data['baseSize'];
    }
}