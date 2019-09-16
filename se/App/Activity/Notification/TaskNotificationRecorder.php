<?php
namespace Platform\App\Activity\Notification;

use Carbon\Carbon;
use Platform\App\Helpers\Helpers;

/**
* 
*/
class TaskNotificationRecorder
{
	public $receiver = [];
	
	public function record($model, $eventName, $entityId)
	{
		return $this->getData($model, $eventName, $entityId);
	}

	private function getData($model, $event, $entityId)
	{
		$data = [];
		$fields = $model->getDirty();
		if($event == 'updated'){
			foreach ($fields as $key => $value) {
				if($key != 'updated_at' && $key != 'created_at' && $key != 'location' 
					&& $key != 'submission_date' && $key != 'completion_date' && $key != 'is_completed' && $key != 'is_submitted'){
					if($key == 'priority_id'){
						array_push($data, $this->getValues($key, $model->priority->priority, $model, $event, $entityId));
					} else {
						array_push($data, $this->getValues($key, $value, $model, $event, $entityId));
					}
				}
			}
		} elseif($event == 'created'){
			if(isset($model->getDirty()['type']) && $model->getDirty()['type'] == 'text'){
				array_push($data, $this->getValues('comment', 'comment', $model, 'add', $entityId));
			} elseif(isset($model->getDirty()['type']) && $model->getDirty()['type'] == 'file'){
				array_push($data, $this->getValues('file', 'file', $model, 'upload', $entityId));
			}
			array_push($data, $this->getValues('new created', 'new', $model, $event, $entityId));
		}
		return $data;
	}

	private function getValues($key, $value, $model, $event, $entityId){
		if($event == 'updated'){
			$event = 'update';
		}
		$fieldName = ($key == 'new created') ? $model->getAction() : $key;
		$verb = ($event == 'created') ? 'create' : $this->getVerb($key, $model);
		if($verb == 'status check'){
			$verb = $event;
		}
		$data = [
			'version' => getenv('NOTIFICATION_VERSION'),
			'entityId' => $entityId,
			'priority' => (in_array($key, $this->getMaxPriorityKeys()))? 'P1' : 'P2',
			'actor' => $this->getActor(),
			'verb' => $verb,
			'meta' => $model->getMeta(),
			'links' => $this->getChangedData($key, $value, $model),
			'createdAt' => Carbon::now()->toDateTimeString(),
			'receiver' => $this->setReceiver($fieldName, $model)
		];
		return $data;
	}

	public function getChangedData($key, $updated, $model)
	{
		$fieldName = ($key == 'new created') ? $model->getAction() : $key;
		if($fieldName == 'priority_id'){
			$lastPriority = explode('|', $model->getValues()['priority_id']);
			$originalValue = $lastPriority[$model->getOriginal()[$key] - 1];
		} elseif($fieldName == 'assignee_id'){
			$user = \App\User::find($model->getOriginal()[$key]);
			$lastAssignee = explode('|', $model->getRelations()[$key]);
			$updated = [ 'id' => $model->assignee->id, 'name' => $model->assignee->display_name, 'email'=>$model->assignee->email];
			$originalData = (new $lastAssignee[1])->transform($user);
			$originalValue = ['id' => $originalData['id'], 'name' => $originalData['displayName'], 'email'=> $originalData['email']];
		} elseif ($fieldName == 'status_id') {
			$original = explode('|', $model->getValues()[$fieldName]);
			$updated = $model->status->status;
			$originalValue = $original[$model->getOriginal()['status_id'] - 1];
		} elseif ($fieldName == 'comment') {
			$original = explode('|', $model->getValues()[$fieldName]);
			$updated = $model->getDirty()['data'];
			$originalValue = NULL;
		} elseif ($fieldName == 'file') {
			$original = explode('|', $model->getValues()[$fieldName]);
			$updated = $model->getDirty()['data'];
			$originalValue = NULL;
		} else {
			$originalValue = isset($model->getOriginal()[$key]) ? $model->getOriginal()[$key] : NULL;
		}
		return [
			'type' => ($fieldName == 'file')?'image':'event',
			'fieldName' => $this->snakeCaseToCamelCase($fieldName),
			'updatedValue' => ($updated == 'new') ? $model->getMeta() : $updated,
			'originalValue' => $originalValue
		];
	}

	private function snakeCaseToCamelCase($key)
	{

		$exception = [];
		if(in_array($key, $exception) === false){
			return Helpers::snakeCaseToCamelCase($key);
		} else {
			return $key;
		}
	}

	private function getMaxPriorityKeys()
	{
		return [
			'due_date', 'assignee_id', 'status_id', 'new created'
		];
	}

	private function getActor()
	{
		$user = \Auth::user();
		if(is_null($user)){
			return [
                "type" => "BOT",
                "user" => [
                    "displayName" => "SE BOT",
                    "email" => "sebot@sourceeasy.com"
                ]
            ];
		}
		return [
            "type"=> "person",
            "user"=> [
                "id"=> $user->id,
                "displayName"=> $user->display_name,
                "email"=> $user->email,
            ]
        ];
	}

	private function getVerb($key, $model)
	{
		if(is_null($model->getVerbs())) {
            return $model->getModelVerbs();
        }
		if(array_key_exists($key, $model->getVerbs()))
		{
			if($key == 'assignee_id' || $key == 'seen'){
				return $model->getVerbs()[$key];
			} elseif($key == 'is_submitted'){
				return 'submitted or not';
			} elseif($key == 'status_id'){
				return 'status check';
			} elseif($key == 'comment'){
				return 'comment';
			} elseif($key == 'file'){
				return 'upload';
			}
			return $key;
		}
		return 'update';
	}

	private function setReceiver($fieldName, $model)
	{
		if($fieldName == 'created'){
			return [$model->assignee->email];
		} elseif($fieldName == 'title' || $fieldName == 'description' || $fieldName == 'due_date' || 
			$fieldName == 'priority_id' || $fieldName == 'seen') {
			return [$model->assignee->email];
		} elseif($fieldName == 'follower'){
			return [$model->user->email];
		} elseif($fieldName == 'assignee_id'){
			return [$model->assignee->email];
		} elseif($fieldName == 'file'){
			$receiver = [$model->tasks->assignee->email, $model->tasks->creator->email];
			return array_values(array_unique($receiver));
		} elseif ($fieldName == 'status_id') {
			return [$model->creator->email];
		} elseif($fieldName == 'comment'){
			$receiver = [$model->tasks->assignee->email, $model->tasks->creator->email];
			return array_values(array_unique($receiver));
			/**
			 * To get list of all followers as receivers
			 */
			$followerCollection = $model->tasks->followers()->with(['user'])->get();
			foreach ($followerCollection as $key => $value) {
				array_push($receiver, (new \Platform\Users\Transformers\MetaUserTransformer)->transform($value->user)); 
			}
			/**
			 * To get assignee as receivers
			 */
			array_push($receiver, (new \Platform\Users\Transformers\MetaUserTransformer)->transform($model->tasks->assignee));	
			/**
			 * To get creator as receivers
			 */
			array_push($receiver, (new \Platform\Users\Transformers\MetaUserTransformer)->transform($model->tasks->creator));
			$actor = (new \Platform\Users\Transformers\MetaUserTransformer)->transform(\Auth::user());
			$notificationFor = array_filter($receiver, function($element) use ($actor){
				return !in_array($element, [$actor]);
			});
			return array_unique(array_column($notificationFor, 'email'))	;			
		} else{
			// dd($fieldName, "Else Condition se");
		}
		return $this->receiver;
	}

	private function frame($data)
	{
		return [
			'id' => $data->id,
			'name' => $data->display_name,
			'email' => $data->email
		];
	}
}