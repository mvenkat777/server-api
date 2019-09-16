<?php
namespace Platform\Observers\Tasks;

use Platform\App\Activity\ActivityObserver;
use Platform\Observers\Notifiers\TaskActivityNotifier;
use App\User;

class TaskFollowerObserver extends ActivityObserver
{
	/**
     * @param array $model
     * @return mixed
     */
	public function created($model){
		$verb = 'updated';
		$object = [
			'objectType' => 'follower',
			'message' => \Auth::user()->display_name.' added '.$this->getFollowerDetails($model).' as follower of this task .',
			'task' => (new \Platform\Tasks\Transformers\FollowerTransformer)->transform($model),
		];
		$this->setActivityVerb($verb)->setObject($object)->update($model, 'task');
		return "Successfully Added To Mongo DB.";
	}

	/**
     * @param array $model
     * @return mixed
     */
	// public function updated($model){
	// 	$collection = [ 'updated_at', 'created_at' ];
	// 	foreach ($model->getDirty() as $key => $value) {
	// 		if (!in_array($key, $collection)) {
	// 			if(is_string($value) && is_array(json_decode($value, true)) && 
	// 				(json_last_error() == JSON_ERROR_NONE) ? true : false) {
	// 				$value = json_decode($value)->email; 
	// 		    }
	// 			$object = [
	// 					'task_id' => $model->id,
	// 					'objectType' => $key,
	// 					'message' => \Auth::user()->display_name.'('.\Auth::user()->email.') updated '.$key.' to -'.$value.' .'
	// 				];
	// 				$this->setObjectKey($key)->setObject($object)->update($model, 'task');
	// 			}
	// 		}
	// 		return "Successfully Added To Mongo DB.";
	// }

	// /**
 //     * @param array $model
 //     * @return mixed
 //     */
	// public function deleted($model){
	// 	$verb = 'follower';
	// 	foreach ($model->getDirty() as $key => $value) {
	// 		$object = [
	// 				'objectType' => 'deleted',
	// 				'task_id' => $model->id,
	// 				'message' => \Auth::user()->display_name.'('.\Auth::user()->email.') removed.'
	// 			];
	// 			$this->setObjectKey($verb)->setObject($object)->delete($model, 'task');
	// 		}
	// 	return "Successfully Added To Mongo DB.";
	// }


	public function getFollowerDetails($collection){
		$details = User::where('id',$collection->follower_id)->first();
		return $details->display_name;
	}
}