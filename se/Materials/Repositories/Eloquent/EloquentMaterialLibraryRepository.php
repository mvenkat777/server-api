<?php

namespace Platform\Materials\Repositories\Eloquent;
use DB;
use Exception;
// use Platform\App\Exceptions\SeException;
use App\MaterialLibrary;
use App\Material;
use App\Vendor;
use Carbon\Carbon;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Materials\Repositories\Contracts\MaterialLibraryRepository;

class EloquentMaterialLibraryRepository extends Repository implements MaterialLibraryRepository
{
     /**
     * Return the models
     * @return string
      */
    public function model()
    {
        return 'App\MaterialLibrary';
    }

    /**
     * Create a new Material
     * @param  array $data
     * @return App\Material
     */
    
    public function createMaterialLibrary($objs)
    {         
        //dd($data);
        //$digits = 7;
        //$random_number = rand(pow(10, $digits-1), pow(10, $digits)-1);
         //\DB::beginTransaction(); 
          //$respondLibraryItems =[];
          //foreach ($data as $num => $objs)
         //  {
            //dd($objs);
           $insertData = [
            'id' => $this->generateUUID(),
            'material_id' => $objs['materialId'],
            'vendor_id' => $objs['vendorId'],
            'fabric_reference' => $objs['fabric_reference'],
            'fabric_style' => $objs['fabricStyle'],
            'cost_local' => $objs['costLocal'],
            'cost_usd' => $objs['costUsd'],
            'cost_uom' => $objs['costUom'],
            'stock' => $objs['stock'],
            'avail_greige' => $objs['availGreige'],
            'notes' => $objs['notes'],
            //'major_customer' => $objs['majorCustomer'],
            //'print_cost' => $objs['printCost'],
            'primary_print_vendor' => $objs['primaryPrintVendor'],
            'primary_print_vendor_cost_uom' => $objs['primaryPrintVendorCostUom'],
            'primary_print_vendor_cost_usd' => $objs['primaryPrintVendorCostUsd'],
            'primary_print_vendor_cost_local' => $objs['primaryPrintVendorCostLocal'],
            'secondary_print_vendor' => $objs['secondaryPrintVendor'],
            'secondary_print_vendor_cost_uom' => $objs['secondaryPrintVendorCostUom'],
            'secondary_print_vendor_cost_usd' => $objs['secondaryPrintVendorCostUsd'],
            'secondary_print_vendor_cost_local' => $objs['secondaryPrintVendorCostLocal'],
            'fabric_lead_time' => $objs['fabricLeadTime'],
            'minimum_order_quantity' => !empty($objs['minimumOrderQuantity'])?$objs['minimumOrderQuantity']:null,
            'minimum_order_quantity_uom' => $objs['minimumOrderQuantityUom'],
            'minimum_order_quantity_surcharge' => !empty($objs['minimumOrderQuantitySurcharge'])?$objs['minimumOrderQuantitySurcharge']:null,
            'minimum_order_quantity_surcharge_usd' => !empty($objs['minimumOrderQuantitySurchargeUsd'])?$objs['minimumOrderQuantitySurchargeUsd']:null,
            'minimum_color_quantity' => !empty($objs['minimumColorQuantity'])?$objs['minimumColorQuantity']:null,
            'minimum_color_quantity_uom' => $objs['minimumColorQuantityUom'],
            'minimum_color_quantity_surcharge' => !empty($objs['minimumColorQuantitySurcharge'])?$objs['minimumColorQuantitySurcharge']:null,
            'minimum_color_quantity_surcharge_usd' => !empty($objs['minimumColorQuantitySurchargeUsd'])?$objs['minimumColorQuantitySurchargeUsd']:null,
            'library_attachment' => !empty($objs['libraryAttachment'])?json_encode($objs['libraryAttachment']):null,
                ];
           //$eachItem =$this->create($insertData);
           //array_push($respondLibraryItems,$eachItem);
        //}
        //DB::commit();
        return $this->create($insertData);       
    }

    /**
     * Update Material
     * @param  array $data
     * @return App\Material
    */ 
    public function updateMaterialLibrary($objs)
    { 
        //foreach ($data as $key => $objs) {

            $materialitem = [
                'material_id' => $objs['materialId'],
                'vendor_id' => $objs['vendorId'],
                'fabric_style' => $objs['fabricStyle'],
                'cost_local' => $objs['costLocal'],
                'cost_usd' => $objs['costUsd'],
                'cost_uom' => $objs['costUom'],
                'stock' => $objs['stock'],
                'avail_greige' => $objs['availGreige'],
                'notes' => $objs['notes'],
                'fabric_reference' => $objs['fabricReference'],
                //'major_customer' => $this->updateCustomers($objs['majorCustomer']),
                //'print_cost' => $objs['printCost'],
                'primary_print_vendor' => $objs['primaryPrintVendor'],
                'primary_print_vendor_cost_uom' => $objs['primaryPrintVendorCostUom'],
                'primary_print_vendor_cost_usd' => $objs['primaryPrintVendorCostUsd'],
                'primary_print_vendor_cost_local' => $objs['primaryPrintVendorCostLocal'],
                'secondary_print_vendor' => $objs['secondaryPrintVendor'],
                'secondary_print_vendor_cost_uom' => $objs['secondaryPrintVendorCostUom'],
                'secondary_print_vendor_cost_usd' => $objs['secondaryPrintVendorCostUsd'],
                'secondary_print_vendor_cost_local' => $objs['secondaryPrintVendorCostLocal'],
                'fabric_lead_time' => $objs['fabricLeadTime'],
                'minimum_order_quantity' => $objs['minimumOrderQuantity'],
                'minimum_order_quantity_surcharge' => $objs['minimumOrderQuantitySurcharge'],
                'minimum_order_quantity_uom' => $objs['minimumOrderQuantityUom'],
                'minimum_order_quantity_surcharge_usd' => $objs['minimumOrderQuantitySurchargeUsd'],
                'minimum_color_quantity' => $objs['minimumColorQuantity'],
                'minimum_color_quantity_surcharge' => $objs['minimumColorQuantitySurcharge'],
                'minimum_color_quantity_uom' => $objs['minimumColorQuantityUom'],
                'minimum_color_quantity_surcharge_usd' => $objs['minimumColorQuantitySurchargeUsd'],
                'library_attachment' =>  json_encode($objs['libraryAttachment']) 
            ];
            // dd($materialitem);
            
        $this->model->where('id', '=', $objs['materialLibraryId'])->update($materialitem);
        return $this->showMaterialLibraryById($objs['materialLibraryId']);
        // return ;
    }
    // dd($materialitem);

    /**
     * Get the materials
     * @return App\Material
    */ 
    public function getMaterialReference($refno)
    {
        /*return $this->model->where('fabric_reference', 'ILIKE', $refno. '%')->orderBy('created_at', 'desc')->orderBy('fabric_reference', 'desc')->first();*/
        //dd($refno);
       /* return $this->model->where('fabric_reference', 'ILIKE', $refno. '%')->orderBy('created_at', 'desc')->orderByRaw('substr(material_library.fabric_reference,21) desc')->first();*/

       return $this->model->where('fabric_reference', 'ILIKE', $refno. '%')->orderBy('fabric_reference', 'desc')->first();
    }

    /**
     * Get all the materials
     * @return App\Material
    */ 
    public function getAllMaterialLibrarys($command)
    {
        return $this->model->orderBy('updated_at', 'desc')->paginate($command->item);
    }

    /**
     * Get the materials
     * @return App\Material
    */ 
    public function showMaterialLibraryById($materialId)
    {
        return $this->model->where('id', '=', $materialId)->first();
    }

    /**
     * @param  array $data
     * @return mixed
     */
    public function filterMaterialLibrary($data)
    {
        $item = isset($data['item'])? $data['item'] : config('constants.listItemLimit');
        
        if(isset($data['fibers']) && !empty($data['fibers'])){
            $inputFiber = $data['fibers'];
            $searchMainFibers = ['materials.fiber_1'=> $inputFiber , 'materials.fiber_2'=> $inputFiber , 'materials.fiber_3'=> $inputFiber ];

            // $x = $this->model->join('materials', 'materials.id', '=', 'material_library.material_id')->orWhere($search)->toSql();
            return $this->model->join('materials', 'materials.id', '=', 'material_library.material_id')->where(function($query) use($inputFiber) {
                $query->orWhere('materials.fiber_1','ILIKE', $inputFiber)
                      ->orWhere('materials.fiber_2', 'ILIKE', $inputFiber)
                      ->orWhere('materials.fiber_3','ILIKE', $inputFiber);
                      //->orWhereRaw("materials.other_fibers ->> 'fiber_name' ILIKE '$inputFiber'");
                      //->orWhere("materials.other_fibers->'fiber_name'",'ILIKE', $inputFiber);
                })->paginate($item);
//             dd($x);        
                       // ->paginate($item);
            //$x = $this->model->with(['material'])->orWhere($search)->toSql();
            //dd($x);
            //$data['weight'] = [$data['weight']-10 , $datao['weight']+10 ]; 
            //dd($x);
        }
        //dd("asf");

        if(isset($data['weight']) && !empty($data['weight'])){
            $data['weight'] = [$data['weight']-10 , $data['weight']+10 ]; 
        }

        return $this->filter($data)->paginate($item);
    }

    public function getAllFabricReferences(){

        //$x = $this->model->select('fabric_reference')->toSql();
        //dd($x);
        return $this->model->lists('fabric_reference');
    }
    
    /**
     * Get all the countries of vendors used in library 
     * @return array
    */ 
    public function getAllUniqueVendorUsedCountries()
    {
        //return $this->model->vendor();
        //return $this->model->select('vendor_id')->groupBy('vendor_id')->get();
        $vendorUniqueData = $this->model->with(['vendor'])->select('vendor_id')->distinct()->get()->toArray();
        //dd($vendorUniqueData);
        $countryCodes = [];
        foreach($vendorUniqueData as $vid){
           $countryCodes[] =  $vid['vendor']['country_code'];
        }
        //dd($countryCodes);

        $countries = \App\Country::whereIn('code',$countryCodes)->get();
        //lists('country','code');

        return $countries;
        
    }

    public function addCustomers($libObj,$customerIds)
    { 
      return $libObj->customers()->attach($customerIds);
    }

    public function updateCustomers($libObj,$customerIds)
    { 
      return $libObj->customers()->sync($customerIds);
    }


    public function getMaterialLibraryPrint($libIds)
    { 
      return $this->model->whereIn('id', $libIds)->get();
    }
    

}
