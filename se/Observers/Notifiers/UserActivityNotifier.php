<?php
namespace Platform\Observers\Notifiers;

use Platform\App\Activity\ActivityNotificationFor;

class UserActivityNotifier extends ActivityNotificationFor
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
			'message' => $message
		];
		$this->setAction($action)->setActivityVerb($verb)->setObject($object)->notifyTo($model);
		return "Successfully Added To Mongo DB.";
	}
}