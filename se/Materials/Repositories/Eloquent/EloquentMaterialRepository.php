<?php

namespace Platform\Materials\Repositories\Eloquent;

use App\Material;
use Carbon\Carbon;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Materials\Repositories\Contracts\MaterialRepository;

class EloquentMaterialRepository extends Repository implements MaterialRepository
{
     /**
     * Return the models
     * @return string
      */
    public function model()
    {
        return 'App\Material';
    }

    /**
     * Create a new Material
     * @param  array $data
     * @return App\Material
     */

    public function createMaterial($data)
    { //dd($data);//dd('IN REPO');

        //$gen = $this->generateMaterialReference($data->materialType);
        //dd($gen);
        $data = [
            'id' => $this->generateUUID(),
            'material_reference_no' => $this->generateMaterialReference($data->materialType),
            'material_type' => $data->materialType,
            'construction' => $data->construction,
            'construction_type' => $data->constructionType,
            'fabric_type' => $data->fabricType,
            'fiber_1' => $data->fiber1,
            'fiber_1_percentage' => $data->fiber1Percentage,
            'fiber_2' => $data->fiber2,
            'fiber_2_percentage' => $data->fiber2Percentage,
            'fiber_3' => $data->fiber3,
            'fiber_3_percentage' => $data->fiber3Percentage,
            'other_fibers' => json_encode($data->otherFibers),
            'weight' => $data->weight,
            'weight_uom' => $data->weightUOM,
            'cuttable_width' => $data->cuttableWidth,
            'width_uom' => $data->widthUOM
        ];
        //dd($data);
        return $this->create($data);
    }

    public function generateMaterialReference($type){

        $type = trim($type);
        $prefix = 'M'.$type[0];

        $incData = $this->getUniqueMaterialIncrement($prefix);
        //dd($incData->toArray());
        if($incData != null){
            $explSuffixData = explode($prefix,$incData->material_reference_no);
            $dbId = (int) $explSuffixData[1];
            $suffix = $dbId+1;
        }else{
            $suffix = 1;
        }

        // For zeros addition START
        $countMaterial = $suffix;
        $strCountMaterial = (string) $countMaterial;
        $strSequenceNo = (int) strlen($strCountMaterial);

        $padLen = 7;
        //dd($strSequenceNo);
        if($strSequenceNo > 1){
            //dd($strSequenceNo);
            $strSequenceNo = $strSequenceNo-1;
            $padLen = $padLen-$strSequenceNo;
        }

        $prefix = str_pad($prefix, $padLen, 0, STR_PAD_RIGHT);
        // For zeros addition END

        //dd($prefix);
        $final = $prefix.$suffix;
        //dd($final);;

        return $final;
    }

    public function getUniqueMaterialIncrement($refno){
        //dd($refno);
        //$x = $this->model->where('material_reference_no', 'ILIKE', $refno. '%')->orderBy('created_at', 'desc')->orderByRaw('substr(materials.material_reference_no,7) desc')->toSql();
        //dd($x);
         return $this->model->where('material_reference_no', 'ILIKE', $refno. '%')->orderBy('material_reference_no', 'desc')->first();

    }

    /**
     * Get all the materials
     * @return App\Material
    */
    public function getAllMaterials($command)
    {
        return $this->model->orderBy('updated_at', 'desc')->paginate($command->item);
    }

    /**
     * Get the materials
     * @return App\Material
    */
    public function showMaterialById($materialId)
    {
        return $this->model->where('id', '=', $materialId)->first();
    }

    /**
     * Update Material
     * @param  array $data
     * @return App\Material
    */
    public function updateMaterial($data)
    {
        //dd($data);
        $material = [
            'material_type' => $data->materialType,
            'construction' => $data->construction,
            'construction_type' => $data->constructionType,
            'fabric_type' => $data->fabricType,
            'fiber_1' => $data->fiber1,
            'fiber_1_percentage' => $data->fiber1Percentage,
            'fiber_2' => $data->fiber2,
            'fiber_2_percentage' => $data->fiber2Percentage,
            'fiber_3' => $data->fiber3,
            'fiber_3_percentage' => $data->fiber3Percentage,
            'other_fibers' => json_encode($data->otherFibers),
            'weight' => $data->weight,
            'weight_uom' => $data->weightUOM,
            'cuttable_width' => $data->cuttableWidth,
            'width_uom' => $data->widthUOM
        ];

        return $this->model->where('id', '=', $data->id)->update($material);
    }

    /**
     * @param  array $data
     * @return mixed
     */
    public function filterMaterial($data)
    {
        $item = isset($data['item'])? $data['item'] : config('constants.listItemLimit');
        return $this->filter($data)->paginate($item);
    }


    public function checkUniqueMaterial($data){
        $finalSearch = [];
        $finalSearch['material_type'] = $data->materialType;
        $finalSearch['construction'] = $data->construction;
        $finalSearch['construction_type'] = $data->constructionType;
        $finalSearch['fabric_type'] = $data->fabricType;
        $finalSearch['fiber_1'] = $data->fiber1;
        $finalSearch['fiber_2'] = $data->fiber2;
        $finalSearch['fiber_3'] = $data->fiber3;
        //$finalSearch['weight'] = $data->weight;
        $tenPercent = ($data->weight * 10)/100;
        $fromWeight = floor($data->weight - $tenPercent);
        $toWeight = ceil($data->weight + $tenPercent);
        //$x = $this->model->whereBetween('weight',[$fromWeight,$toWeight])->get()->toArray();
        //dd($x);
        if(!empty($data->materialId)){
            return $this->model->where($finalSearch)->whereBetween('weight',[$fromWeight,$toWeight])->where('id','!=',$data->materialId)->first();
        }else{
            return $this->model->where($finalSearch)->whereBetween('weight',[$fromWeight,$toWeight])->first();
        }

       /*$test = [];
       $test['query'] = $this->model->where($finalSearch)->whereBetween('weight',[$fromWeight,$toWeight])->toSql();

        $test['binds'] = $this->model->where($finalSearch)->whereBetween('weight',[$fromWeight,$toWeight])->getBindings();
        dd($test);*/
    }

    /**
     * Get all the customers
     * @return boolean

    public function deleteMaterial($command)
    {
        return $this->model->where('id', '=', $materialId)->delete();
    }
*/


}
