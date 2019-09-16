<?php
namespace Platform\Observers\Tasks;

use Platform\App\Activity\ActivityObserver;
use Platform\Observers\Notifiers\TaskActivityNotifier;

class TaskAttachementObserver extends ActivityObserver
{
	/**
     * @param array $model
     * @return mixed
     */
	public function created($model){
			$verb = 'updated';
			$object = [
				'objectType' => 'attachement added',
				'task' => (new \Platform\Tasks\Transformers\AttachmentTransformer)->transform($model)
			];
			$this->setActivityVerb($verb)->setObject($object)->update($model,'task');
			return "Successfully Added To Mongo DB.";
	}
}