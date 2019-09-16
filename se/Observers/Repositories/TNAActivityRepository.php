<?php
namespace Platform\Observers\Repositories;

use App\ActivityModel\ObserverModel\TNAActivityObserver;

class TNAActivityRepository{

	public $data = [];

	public function getTNADetails($command) {
		$newInsertList =  TNAActivityObserver::where('id',$command->tnaId)
												->select('localDate','actor','message','updated_at')
												->orderBy('created_at','desc')
												->get();
        foreach ($newInsertList as $key => $value) {
        	array_push($this->data, $value);
		}
		return $this->data;
	}
}