<?php

namespace Platform\Pom\Repositories\Eloquent;

use App\PomSheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Factory;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Pom\Repositories\Contracts\PomSheetRepository;
use Platform\Pom\Validators\PomSheetValidator;

class EloquentPomSheetRepository extends Repository implements PomSheetRepository 
{

	public function model(){
		return 'App\PomSheet';
	}

	public function getByPomId($pomId)
	{
		return $this->model->where('pom_id', $pomId)
			->where('key', true)
			->get();
	}

	/**
	 * add sheet row
	 * 
	 * @param array $data 
	 * @return array
	 */
	public function addSheetRow($data)
	{
		$data = [
			'pom_id' => $data['pomId'],
			'qc' => $data['qc'],
			'key' => $data['key'],
			'code' => $data['code'],
			'description' => isset($data['description'])? $data['description'] : NULL,
			'tol' => isset($data['tol'])? $data['tol'] : NULL,
			'data' => json_encode($data['data'])
		];
		return $this->create($data);
	}

	/**
	 * update sheet row
	 * 
	 * @param  array $data    
	 * @param  integer $sheetId 
	 * @return boolean       
	 */	
	public function updateOrAddSheetRow($data, $pomId)
	{
		if (isset($data['id']) && $this->model->find($data['id'])) {
			$data1 = [
				'qc' => $data['qc'],
				'key' => $data['key'],
				'code' => $data['code'],
				'description' => isset($data['description'])? $data['description'] : NULL,
				'tol' => isset($data['tol'])? $data['tol'] : NULL,
				'data' => json_encode($data['data'])
			];
			$this->updatePomSizeRangeValue($pomId, $data['data']);
			return $this->model->where('id', $data['id'])
				->where('pom_id', $pomId)
				->update($data1);
		} else {
			$validator = new PomSheetValidator(App::make('Illuminate\Validation\Factory'));
        	$validator->setAddPomSheetRowRule()->validate($data);
			return $this->addSheetRow($data);
		}	
	}

	/**
	 * delete sheet row
	 * 
	 * @param  string $pomId   
	 * @param  integer $sheetId 
	 * @return boolean
	 */
	public function deleteSheetRow($pomId, $sheetId)
	{
		return $this->model->where('pom_id', $pomId)
			->where('id', $sheetId)
			->delete();
	}

	public function archiveSheetRow($pomId, $sheetId)
	{
		return $this->model->where('pom_id', $pomId)
			->where('id', $sheetId)
			->update(['archived_at' => Carbon::now()]);
	}

	public function rollbackSheetRow($pomId, $sheetId)
	{
		return $this->model->where('pom_id', $pomId)
			->where('id', $sheetId)
			->update(['archived_at' => NULL]);
	}

	/**
	 * @param  array $data         
	 * @param  string $requierdData 
	 * @return mixed
	 */
	public function filterPomSheet($data, $requierdData)
	{
		if ($requierdData === 'code') {
			$responseData = [];
			$response = $this->model->select('code')->get();
			foreach ($response as $key => $code) {
				array_push($responseData, $code['code']);
			}
			return $responseData;
		}
        return $this->filter($data)->get();
	}

	public function updatePomSizeRangeValue($pomId, $data)
	{
		$rangeValue = array_keys($data);
		\App\Pom::where('id', $pomId)->update([
			'size_range_value' => json_encode($rangeValue), 
			'size_range_name' => isset($rangeValue[0]) ? $rangeValue[0].'-'.end($rangeValue) : 0
		]);
	}
}