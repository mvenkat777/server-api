<?php 
namespace Platform\App\Activity;

use Carbon\Carbon;
use Platform\App\Activity\Models\ParentEntityNotification as PlatformNotification;
use Platform\App\Activity\Models\ParentLineEntityNotification as PlatformLineNotification;
use Platform\App\Activity\Models\SubEntityNotification;
use Platform\App\Activity\Models\SubEntityLineNotification;
use Platform\App\Activity\Models\UserNotification;
use Platform\App\Activity\Models\UserLineNotification;
use Vinkla\Pusher\PusherManager;
use Illuminate\Contracts\Config\Repository;
use Vinkla\Pusher\Facades\Pusher;

trait NotificationRecorder
{
	public $pusher;

	public function recordNotification($model, $event)
	{
		if(is_object($model)){
			$this->findClass($model, $event);
		}
	}

	public function recordCustomNotification($model, $data, $event, $modelName)
	{
		if(strtolower($modelName) == 'style'){
			$parentEntity = $this->storeNewEntity($model);
			$entityId = $this->checkIfEntityExists($parentEntity, true);
			if($entityId){
				try{
					$class = '\Platform\App\Activity\Notification\\'.ucfirst($modelName).'NotificationRecorder';
					if(!class_exists($class)){
						return ;
					}
					$subEntity = (new $class)->record($model, $event, $entityId, $data);
					$subEntityIdList = $this->storeSubEntity($subEntity, true);
					if(count($subEntityIdList)){
						$this->updateUserNotification($subEntityIdList, $subEntity, true);
					}
				} catch(Exception $e){
				}
			}
		}
	}

	private function findClass($model, $event){
		if(get_class($model) == 'App\Style'){
			$class = '\Platform\App\Activity\Notification\\'.ucfirst('style').'NotificationRecorder';
		} else {
			$class = '\Platform\App\Activity\Notification\\'.ucfirst($model->appName).'NotificationRecorder';
		}
		if(!class_exists($class))
		{
			return ;
		}
		$parentEntity = $this->storeNewEntity($model);
		if(strtolower($model->appName) == 'line'){
			$entityId = $this->checkIfEntityExists($parentEntity, true);
		} else {
			$entityId = $this->checkIfEntityExists($parentEntity);
		}
		if($entityId){
			try{
				$subEntity = (new $class)->record($model, $event, $entityId);
				if(strtolower($model->appName) == 'line'){
					$subEntityIdList = $this->storeSubEntity($subEntity, true);
				} else {
					$subEntityIdList = $this->storeSubEntity($subEntity);
				}
				if(count($subEntityIdList)){
					
					if(strtolower($model->appName) == 'line'){
						$this->updateUserNotification($subEntityIdList, $subEntity, true);
					} else {
						$this->updateUserNotification($subEntityIdList, $subEntity);
					}
					/**
					 * For Pusher
					 */
					$notifReceiverList = [];
					foreach ($subEntity as $key => $value) {
						if(count($value['receiver']) > 1){
							$notifReceiverList = $value['receiver'];
						} else {
							array_push($notifReceiverList, implode('', $value['receiver']));
						}
					}
					$app = 'all';
					$finalList = array_values(array_unique($notifReceiverList));
					foreach ($finalList as $key => $value) {
						\LaravelPusher::trigger(
				                    'notification-'.$value, 
				                    'Platform Notification', 
				                    ['data' => ['setNotif' => true, 'appName' => $app]]
				                );
					}
				}
			} catch(Exception $e){

			}
		}
	}

	private function updateUserNotification($list, $data, $isStyle = false)
	{
		foreach ($list as $key => $value) {
			$subData = $data[$key];
			$ifExists = $this->checkIfUserHaveNotif($subData['receiver'], $isStyle);
			if($ifExists){
				$this->updateUser($subData['entityId'], $subData['receiver'], $value, $isStyle);
			}
		}
	}

	private function updateUser($entityId, $receiver, $subEntityId, $isStyle = false)
	{
		foreach ($receiver as $key => $email) {
			if($isStyle){
				$user = UserLineNotification::where('userEmail', $email)
									->where('object.entityId', $entityId)
									->get();
			} else{ 
				$user = UserNotification::where('userEmail', $email)
										->where('object.entityId', $entityId)
										->get();
			}
			$subFrame = [
					'id' => $subEntityId,
					'seen' => false
				];
			if(count($user)){
				if($isStyle){
					$update = UserLineNotification::where('userEmail', $email)
											->where('object.entityId', $entityId)
											->push([
											 	'object.$.subEntity' => $subFrame
											 ]);
					$lastSeenUpdate	= UserLineNotification::where('userEmail', $email)
												->where('object.entityId', $entityId)
												->update([
												 	'object.$.updatedAt' => Carbon::now()->toDateTimeString()
												 ]);
				} else {
					$update = UserNotification::where('userEmail', $email)
												->where('object.entityId', $entityId)
												->push([
												 	'object.$.subEntity' => $subFrame
												 ]);
					$lastSeenUpdate	= UserNotification::where('userEmail', $email)
												->where('object.entityId', $entityId)
												->update([
												 	'object.$.updatedAt' => Carbon::now()->toDateTimeString()
												 ]);
				}
			} else{
				$newFrame = [
					'entityId' => $entityId,
					'lastSeen' => Carbon::now()->toDateTimeString(),
					'updatedAt' => Carbon::now()->toDateTimeString(),
					'subEntity' => []
				];
				if($isStyle){
					$update = UserLineNotification::where('userEmail', $email)->push('object', $newFrame);
				} else {
					$update = UserNotification::where('userEmail', $email)->push('object', $newFrame);
				}
				if($update){
					if($isStyle){
						$push = UserLineNotification::where('userEmail', $email)
											->where('object.entityId', $entityId)
											->push([
											 	'object.$.subEntity' => $subFrame
											 ]);
					} else{
						$push = UserNotification::where('userEmail', $email)
												->where('object.entityId', $entityId)
												->push([
												 	'object.$.subEntity' => $subFrame
												 ]);
					}
				}
			}
		}
		return true;
	}

	private function checkIfUserHaveNotif($emailList, $isStyle = false)
	{
		foreach ($emailList as $key => $email) {
			if($isStyle){
				$ifExists = UserLineNotification::where('userEmail', $email)->first();
			} else {
				$ifExists = UserNotification::where('userEmail', $email)->first();
			}
			if(!$ifExists) {
				$user = \App\User::where('email', $email)->first();
				if(is_null($user)){
					$userFrame = [
						'userId' => 'se-bot',
						'userEmail' => 'SE-BOT',
						'object' => []
					];
				} else {
					$userFrame = [
						'userId' => $user->id,
						'userEmail' => $user->email,
						'object' => []
					];
				}
				if($isStyle){
					UserLineNotification::create($userFrame);
				} else {
					UserNotification::create($userFrame);
				}
			}
		}
		return true;
	}

	private function storeSubEntity($data, $isStyle = false)
	{
		$idList = [];
		if($isStyle){
			foreach ($data as $key => $value) {
				if(!is_null($value)){
					$subEntity = SubEntityLineNotification::create($value);
					array_push($idList, $subEntity['_id']);
				}
			}
		} else{
			foreach ($data as $key => $value) {
				if(!is_null($value)){
					$subEntity = SubEntityNotification::create($value);
					array_push($idList, $subEntity['_id']);
				}
			}
		}
		return $idList; 
	}

	private function storeNewEntity($model){
		$appInfo = $this->getAppDetails($model);
		return [
            'version' => getenv('NOTIFICATION_VERSION'),
            'entityId' => $this->getNotificationMeta($model)['id'],
            'objectType' => 'notif',
            'rules' => NULL,
            'entity' => [
                'displayName'=> isset($model->appName)? ucfirst($model->appName) : $this->getActivityName($model),
                'systemName'=> isset($model->appName)? strtolower($model->appName) : strtolower($this->getActivityName($model)),
                'meta'=> $this->getNotificationMeta($model),
                'appId'=> $appInfo->id,
                'icon'=> $appInfo->icon,
            ],
            'subEntity' => [],
            'updatedAt' => Carbon::now()->toDateTimeString(),
            'lastSeen' => Carbon::now()->toDateTimeString()
        ];
	}

	private function getNotificationMeta($model)
	{
		if(method_exists($model, 'getParentMeta')) {
            return $model->getParentMeta();
        }
        if (method_exists($model, 'getMeta')) {
            return $model->getMeta();
        }
        return NULL;
	}

	private function getAppDetails($model){
		$appName = isset($model->appName)? strtolower($model->appName) : strtolower($this->getActivityName($model));
        return \App\AppsList::where('app_name', $appName)->first();
	}

	private function checkIfEntityExists($data, $isStyle = false)
	{
		if($isStyle){
			$entity = PlatformLineNotification::where('entityId', $data['entityId'])->first();
			if($entity){
				return $entity->_id;
			} else {
				$isCreated = PlatformLineNotification::create($data);
				if($isCreated){
					return $this->checkIfEntityExists($data, true);
				}
			}
		} else {
			$entity = PlatformNotification::where('entityId', $data['entityId'])->first();
			if($entity){
				$update = PlatformNotification::where('entityId', $data['entityId'])
							 ->update(['entity.meta' => $data['entity']['meta']]);
				return $entity->_id;
			} else {
				$isCreated = PlatformNotification::create($data);
				if($isCreated){
					return $this->checkIfEntityExists($data);
				}
			}	
		}
	}
}