<?php
namespace Platform\Observers\Repositories;

use App\ActivityModel\ObserverModel\TechpackActivityObserver;

class TechpackActivityRepository{

	public $data = [];

	public function getTechpackDetails($command){
		$newInsertList =  TechpackActivityObserver::where('id',$command->techpackId)
												->select('localDate','actor','message','updated_at')
												->orderBy('created_at','desc')
												->get();
        foreach ($newInsertList as $key => $value) {
        	array_push($this->data, $value);
		}
		return $this->data;
	}
}