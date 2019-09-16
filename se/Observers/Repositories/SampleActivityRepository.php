<?php
namespace Platform\Observers\Repositories;

use App\ActivityModel\ObserverModel\SampleActivityObserver;

class SampleActivityRepository{

	public $data = [];

	public function getSampleDetails($command){
		$newInsertList =  SampleActivityObserver::where('id',$command->sampleId)
												->select('localDate','actor','message','updated_at')
												->orderBy('created_at','desc')
												->get();
        foreach ($newInsertList as $key => $value) {
        	array_push($this->data, $value);
		}
		return $this->data;
	}
}