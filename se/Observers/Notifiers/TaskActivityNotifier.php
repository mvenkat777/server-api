<?php
namespace Platform\Observers\Notifiers;

use Platform\App\Activity\ActivityNotificationFor;

class TaskActivityNotifier extends ActivityNotificationFor
{
	/**
     * @param array $model
     * @param $message
     * @param array $action
     * @return string
     */
	public function makeNewNotification($model, $message, $action){
		$lastInsertedId = $this->getLastCreateActivityInsertedIndexId();
		$verb = 'notify';
		$object = [
			'id' => json_decode($lastInsertedId)[0]->_id,
			'objectType' => 'Notification',
			'title' => $this->getTitle($model),
			'description' => $this->getDescription($model),
			'message' => $message
		];
		$this->setAction($action)->setActivityVerb($verb)->setObject($object)->notifyTo($model);
		return "Successfully Added To Mongo DB.";
	}

	/**
     * @param array $model
     * @return mixed
     */
	protected function getTitle($model){
		if(isset($model->type)){
			return $model->type;
		}
		elseif(isset($model->title)){
			return $model->title;
		}
		else{
			return 'No Title';
		}
	}

	/**
     * @param array $model
     * @return mixed
     */
	protected function getDescription($model){
		if(isset($model->data)){
			return $model->data;
		}
		elseif(isset($model->description)){
			return $model->description;
		}
		else{
			return 'No Description';
		}
	}
}