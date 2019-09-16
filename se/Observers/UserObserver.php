<?php
namespace Platform\Observers;

use Platform\App\Activity\ActivityObserver;
use Platform\Observers\Notifiers\UserActivityNotifier;

class UserObserver extends ActivityObserver
{
	/**
     * @param array $model
     * @return mixed
     */
	public function created($model){
		$verb = 'created';
		$object = [
			'objectType' => 'User Created',
			'message' => 'Created an account with email id '.$model->email
		];
		$this->setActivityVerb($verb)->setObject($object)->create($model);
		$action = 'Account_Created';
		$notifiers = new UserActivityNotifier();
		$message = 'Created an account with email id '.$model->email;
		$notifiers->makeNewNotification($model, $message, $action);
		return "Successfully Added To Mongo DB.";
	}

	/**
     * @param array $model
     * @return mixed
     */
	public function updated($model){
		$collection = [ 'updated_at', 'created_at' ];
		foreach ($model->getDirty() as $key => $value) {
			if (!in_array($key, $collection)) {
				$object = [
						'objectType' => $key,
						'message' => $model->email. ' has updated '.$key
					];
					$this->setObjectKey($key)->setObject($object)->update($model);
				}
			}
		return "Successfully Added To Mongo DB.";
	}

	/**
     * @param array $model
     * @return mixed
     */
	public function deleted($model){
		foreach ($model->getDirty() as $key => $value) {
			$object = [
					'objectType' => $key,
					'message' => $key.' has been deleted by '.$model->email
				];
				$this->setObjectKey($key)->setObject($object)->delete($model);
			}
		return "Successfully Added To Mongo DB.";
	}
}