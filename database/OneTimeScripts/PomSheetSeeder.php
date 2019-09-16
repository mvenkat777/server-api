<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Rhumsaa\Uuid\Uuid;

class PomSheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
    	DB::table('poms')->delete();
    	DB::table('pom_sheets')->delete();
        $sheets = scandir(base_path().'/resources/assets/sheets');
        unset($sheets[0]);
        unset($sheets[1]);
        foreach ($sheets as $sheet) {
        	$data = file_get_contents (base_path().'/resources/assets/sheets/'.$sheet);
			$jsons = json_decode($data, true);

			foreach ($jsons as $key => $json) {
				foreach ($json as $childKey => $child) {
					$childValue = false;
					foreach ($child as $subChildKey => $subChild) {
						if($childKey === 'KEY' || $childKey === 'QC'){
							if ($subChild === '*') {
								$childValue = true;
								break;
							}
							break;
						} else{
							$childValue = $subChild;
							break;
						}
					}
					$json[$childKey] = $childValue;

				}
				$json['CODE'] = $json['POM_CODE'];
				unset($json['POM_CODE']);
				$json['DESCRIPTION'] = $json['POM_DESCRIPTION'];
				unset($json['POM_DESCRIPTION']);
				foreach ($json as $key1 => $value) {
					if (!($key1 === 'KEY' || $key1 === 'QC' || $key1 === 'CODE' || $key1 === 'DESCRIPTION' || $key1 === 'TOL')){
						$json['data'][$key1] = $value;
						unset($json[$key1]);
					}
				}
				$jsons[$key] = array_change_key_case($json, CASE_LOWER);
			}
			$baseSize = "";
			$start = key($jsons[0]['data']);
			end($jsons[0]['data']);
			$end = key($jsons[0]['data']);
			$sizeRangeName = $start.'-'.$end;
			$sizeRangeValue = [];
			foreach ($jsons[0]['data'] as $key => $value) {
				if ($value == "") {
					$baseSize = $key;
				}
				$sizeRangeValue[] = $key;
			}
			$pomName = rtrim($sheet, '.json').'-'.$baseSize;
			$break = explode('-', $pomName);
			$sizeType = ['A' => 1, 'N' => 2];
			$categories = [
	            '01'=> 'men',
	            '02'=> 'women',
	            '03'=> 'boys',
	            '04'=> 'girls',
	            '05'=> 'juniors',
	            '06'=> 'plus_women',
	            '07'=> 'big/Tall_men',
	            '08'=> 'babies',
	            '09'=> 'toddler',
	            '10'=> 'infant',
	            '11'=> 'kids',
	            '12'=> 'unisex',
	            '13'=> 'accessories',
	            '99'=> 'others',
        	];
        	$productTypes = [
	            '01' => 'TOP',
	            '02' => 'BOTTOM',
	            '03' => 'ONEPIECE',
	            '04' => 'SCARF',
	            '05' => 'HAT',
	            '06' => 'BAG',
	            '07' => 'BLANKET',
	            '99' => 'OTHERS'
	        ];
        	$categories = array_flip($categories);
        	$productTypes = array_flip($productTypes);

			$pom = [
	        	'id' => Uuid::uuid4()->toString(),
	        	'name' => $pomName,
	            'category_code' => (string)$categories[$break[0]],
	            'product_type_code' => (string)$productTypes[$break[2]],
	            'size_range_name' => $sizeRangeName,
	            'size_range_value' => json_encode($sizeRangeValue),
	            'size_type_id' => $sizeType[$break[1]],
	            'base_size' => $baseSize,
	        ];
	        \DB::beginTransaction();
	        $pomResponse = \App\Pom::create($pom)->toArray();
	        foreach ($jsons as $key => $json) {
	        	$json['pom_id'] = $pomResponse['id'];
	        	$json['data'] = json_encode($json['data']);
	        	$sheetResponse = \App\PomSheet::create($json)->toArray();
	        }
	        \DB::commit();
	    }
	}
}