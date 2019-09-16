<?php

use Illuminate\Database\Seeder;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Materials\Commands\CreateMaterialCommand;
use App\Vendor;
use Carbon\Carbon;
use Platform\Materials\Repositories\Eloquent\EloquentMaterialLibraryRepository;

class MaterialDataDumpSeeder extends Seeder
{

	protected $commandBus;

	protected $materialLibraryRepository;
    
	function __construct(DefaultCommandBus $commandBus,
						 EloquentMaterialLibraryRepository $materialLibraryRepository)     
    {
       $this->commandBus = $commandBus;
       $this->materialLibraryRepository = $materialLibraryRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('materials')->delete();
        DB::table('material_library')->delete();
        DB::table('material_library_customers')->delete();
       
        //$vendors = Vendor::whereNull('archived_at')->whereNull('deleted_at')->lists('code', 'id')->toArray();
        $vendors = Vendor::whereNull('archived_at')->lists('id', 'code')->toArray();
        //dd($vendors);
        $filesList = [  'MaterialLibraryDataChinaKnits.csv',
        				'MaterialLibraryDataChinaWoven.csv',
        				'MaterialLibraryDataIndiaKnits.csv',
        				'MaterialLibraryDataIndiaWoven.csv',
        				];

        foreach($filesList as $file){
        	$materialExcelData = \Excel::load(base_path().'/resources/assets/material_data/'.$file)->get()->toArray();

        	$this->materialDumper($materialExcelData,$vendors);    
            //dd('CHECK FIRST');
            //dd($materialLibrary);
        	echo $file." DONE";

        }				
        	
        
        
        //dd('Material Data tables cleaned');
        dd('Material Data Seeded');
    }

    public function materialDumper($materialExcelData,$vendors){
    	$unFoundVendors = [];
        foreach ($materialExcelData as $key => $materialRow) {
        	//dd($materialRow);
            //dd($vendors[trim($materialRow['supplier'])]);            
            if(isset($vendors[trim($materialRow['supplier'])])){
            	//dd('TEST');
	            $materialMasterCode = [
					    "materialType" => "Fabric",
					    "construction" => $materialRow['construction_k_w_o'],
					    "constructionType" => (strtoupper($materialRow['construction_k_w_o']) == 'KNIT')?$materialRow['if_knit_warp_or_circular']:$materialRow['if_woven_dimensional_construction_yarn_count'],
					    "fabricType"  =>  $materialRow['fabric_type'],
					    "fiber1"  =>  $materialRow['fiber_1'],
					    "fiber1Percentage"  =>  (int)$materialRow[0],
					    "fiber2"  =>  $materialRow['fiber_2'],
					    "fiber2Percentage"  =>  (int)$materialRow[1],
					    "fiber3"  =>  $materialRow['fiber_3'],
					    "fiber3Percentage"  =>  (int)$materialRow[2],
					    "otherFibers" =>   null,
					    "weight"  =>  (int)$materialRow['weight'],
					    "weightUOM"  =>  $materialRow['wt_uom'],
					    "cuttableWidth"  =>  (int)$materialRow['cuttable_width'],
					    "widthUOM"  =>  $materialRow['width_uom']
	            ];
	            //dd($materialMasterCode);
	            $masterCode = $this->commandBus->execute(new CreateMaterialCommand($materialMasterCode));

	            //dd($masterCode->toArray());
	            $notes = "";
	            if(!is_null($materialRow['other_construction_notes_ie_yarn_count_good_for_printing_etc'])){
	            	$notes = $materialRow['other_construction_notes_ie_yarn_count_good_for_printing_etc']." ";	
	            }
	            if(!empty($materialRow['moq_mcq']) && !is_null($materialRow['moq_mcq'])){
	            	$notes .= $materialRow['moq_mcq'];
	            }

	            $costLocal = '';
	            if(isset($materialRow['costrmb'])){
	            	$costLocal = $materialRow['costrmb'];
	            }elseif(isset($materialRow['costrs'])){
	            	$costLocal = $materialRow['costrs'];	
	            }
	             
	            $materialLibrary = [
				    "fabric_reference"  =>  $materialRow['se_fabric_reference'],    
				    "materialId" => $masterCode->toArray()['id'],
				    "vendorId" => $vendors[trim($materialRow['supplier'])],
				    "fabricStyle"  =>  strtoupper($materialRow['supplier_fabric_style']) ,
				    "costLocal"  =>  (float)$costLocal,
				    "costUsd"  =>  $materialRow['cost_usd'],
				    "costUom"  =>  ucfirst(strtolower($materialRow['cost_uom'])),
				    "stock"  =>  ucfirst(strtolower($materialRow['stock_or_mill'])),
				    "availGreige"  =>  ucfirst(strtolower($materialRow['avail_in_greige'])),
				    "notes"  =>  $notes,
				    "majorCustomer"  =>  null,
				    "printCost"  =>  null,
				    "primaryPrintVendor"  =>  null,
				    "primaryPrintVendorCostUom"  =>  null,
				    "primaryPrintVendorCostLocal"  =>  null,
				    "primaryPrintVendorCostUsd"  =>  null,
				    "secondaryPrintVendor"  =>  null,
				    "secondaryPrintVendorCostUom"  =>  null,
				    "secondaryPrintVendorCostLocal"  =>  null,
				    "secondaryPrintVendorCostUsd"  =>  null,
				    "fabricLeadTime"  =>  null,
				    "minimumOrderQuantity"  =>  (int)$materialRow['moq'] ,
				    "minimumOrderQuantityUom"  =>  $materialRow['moq_uom'] ,
				    "minimumOrderQuantitySurcharge"  =>  null ,
				    "minimumOrderQuantitySurchargeUsd"  =>  null ,
				    "minimumColorQuantity"  =>  (int)$materialRow['mcq'] ,
				     "minimumColorQuantityUom"  =>  $materialRow['mcq_uom'] ,
				    "minimumColorQuantitySurcharge"  =>  null ,
				    "minimumColorQuantitySurchargeUsd"  =>  null ,
				    "libraryAttachment" => null
	            ];

	            $this->materialLibraryRepository->createMaterialLibrary($materialLibrary);
	        }else{
	        	$unFoundVendors[] = $materialRow['supplier'];
	        }
    	}
    	//print_r($unFoundVendors);
	}

}
