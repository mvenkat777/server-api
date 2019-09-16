<?php
namespace Platform\Observers\Tasks;

use Platform\App\Activity\ActivityObserver;
use Platform\Observers\Notifiers\TaskActivityNotifier;

class TaskObserver extends ActivityObserver
{
	/**
     * @param array $model
     * @return mixed
     */
	public function created($model){
			$verb = 'created';
			$object = [
				'objectType' => 'created',
				'task' => (new \Platform\Tasks\Transformers\MetaTaskTransformer)->transform($model)
			];
			$this->setActivityVerb($verb)->setObject($object)->create($model, 'task');
			return "Successfully Added To Mongo DB.";
	}

	/**
     * @param array $model
     * @return mixed
     */
	public function updated($model){
		$transformedData = ((new \Platform\Tasks\Transformers\TaskTransformer)->transform($model));
		$collection = [ 'updated_at', 'created_at' ];
		foreach ($model->getDirty() as $key => $value) {
			if (!in_array($key, $collection)) {
			    if(array_key_exists($key, $this->checkArrayExists())){

                    if($key === 'seen' && empty($model->seen)) {
                        continue;
                    }

			    	if(array_key_exists($this->checkArrayExists()[$key],$transformedData)){
			    		$object = [
							'objectType' => $this->checkArrayExists()[$key],
							'task' => (new \Platform\Tasks\Transformers\MetaTaskTransformer)->transform($model)
						];
						$this->setActivityVerb('updated')->setObject($object)->update($model, 'task');
			    	}
			    }
			}
		}
		return "Successfully Added To Mongo DB.";
	}

	// /**
 //     * @param array $model
 //     * @return mixed
 //     */
	// public function deleted($model){
	// 	$verb = 'task';
		
	// 	foreach ($model->getDirty() as $key => $value) {
	// 		$object = [
	// 				'objectType' => 'created',
	// 				'task' => (new \Platform\Tasks\Transformers\MetaTaskTransformer)->transform($model)
	// 			];
	// 			$this->setActivityVerb('deleted')->setObjectKey($verb)->setObject($object)->delete($model, 'task');
	// 		}
	// 	return "Successfully Added To Mongo DB.";
	// }

	public function taskReject($model) {
		$verb = 'rejected';
		$object = [
			'objectType' => 'rejected task',
			'task' => (new \Platform\Tasks\Transformers\MetaTaskTransformer)->transform($model)
		];
		$object['task']['note'] = $model->note;
		$this->setActivityVerb($verb)->setObject($object)->create($model, 'task');
		return "Successfully Added To Mongo DB.";
	}

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
