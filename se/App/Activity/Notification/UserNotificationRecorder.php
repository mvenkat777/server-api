<?php
namespace Platform\App\Activity\Notification;

use Carbon\Carbon;
use App\Role;
use App\Group;

/**
* For User Notification : UserNotificationRecorder
*/
class UserNotificationRecorder
{
    public $receiver = [];
    
    public function record($model, $eventName, $entityId)
    {
        if($eventName == 'created'  && !is_null($model->email)){
            return $this->getData($model, $eventName, $entityId);
        } else {
            return [];
        }
    }
    private function getData($model, $eventName, $entityId)
    {
        $data = [];
        array_push($data, $this->getValues($eventName, 'new', $model, $eventName, $entityId));
        return $data;
    }

    private function getValues($key, $value, $model, $event, $entityId){
        $changedData = $this->getChangedValues($model);
        $data = [
            'version' => getenv('NOTIFICATION_VERSION'),
            'entityId' => $entityId,
            'priority' => 'P2',
            'actor' => $this->getActor(),
            'verb' => 'create',
            'meta' => $model->getMeta(),
            'links' => $changedData,
            'createdAt' => Carbon::now()->toDateTimeString(),
            'receiver' => $this->getGroupReceiver()
        ];
        return $data;
    }

    private function getChangedValues($model)
    {
        return [
            [
                'type' => 'event',
                'fieldName' => 'id',
                'updatedValue' => $model->id,
                'originalValue' => NULL
            ],
            [
                'type' => 'event',
                'fieldName' => 'email',
                'updatedValue' => $model->email,
                'originalValue' => NULL
            ],
            [
                'type' => 'event',
                'fieldName' => 'displayName',
                'updatedValue' => $model->display_name,
                'originalValue' => NULL
            ]
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

    protected function getGroupReceiver()
    {
        $group = Group::where('name', 'productteam')->first();
        if($group)
        {
            $groupId = $group->id;
            $role = Role::where('group_id', $groupId)->first();
            if($role)
            {
                return array_column($role->users->toArray(), 'email');
            }
        }
    }
}