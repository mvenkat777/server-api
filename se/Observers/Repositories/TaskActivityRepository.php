<?php
namespace Platform\Observers\Repositories;

use App\ActivityModel\ObserverModel\TaskActivityObserver;

class TaskActivityRepository{

	public $data = [];

	public function getTaskDetails($command){
		$newInsertList =  TaskActivityObserver::where('id',$command->taskId)
												->select('localDate','actor','message','updated_at')
												->orderBy('created_at','desc')
												->get();
        foreach ($newInsertList as $key => $value) {
        	array_push($this->data, $value);
		}
		return $this->data;
	}
}