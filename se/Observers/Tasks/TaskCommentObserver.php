<?php
namespace Platform\Observers\Tasks;

use Platform\App\Activity\ActivityObserver;
use Platform\Observers\Notifiers\TaskActivityNotifier;

class TaskCommentObserver extends ActivityObserver
{
	/**
     * @param array $model
     * @return mixed
     */
	public function created($model){
		$verb = 'updated';
		$object = [
			'objectType' => 'commented',
			'message' => \Auth::user()->display_name.' commented on the task - "'.$model->data.'"',
			'task' => (new \Platform\Tasks\Transformers\CommentTransformer)->transform($model),
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
	// 					'objectType' => 'comment edited',
	// 					'message' => \Auth::user()->display_name.'('.\Auth::user()->email.') updated '.$key.' to -'.$value.' .',
	// 					'task' => (new \Platform\Tasks\Transformers\CommentTransformer)->transform($model),
	// 				];
	// 				$this->setObjectKey($key)->setObject($object)->update($model);
	// 			}
	// 		}
	// 		return "Successfully Added To Mongo DB.";
	// }

	public function checkArrayExists(){

		$transformed = [
            'id' => 'id',
            'user_id' => 'userId',
            'title' => 'title',
            'description' => 'description',
            'assignee_id' => 'assignee',
            'due_date' => 'dueDate',
            'seen' => 'seen',
            'completion_date' => 'completionDate',
            'priority_id' => 'priority',
          	'status_id' => 'status'
        ];

        return $transformed;
	}
}