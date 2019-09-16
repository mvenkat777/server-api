<?php
namespace Platform\App\Activity\Notification;

use Carbon\Carbon;
use App\Role;
use App\Group;

/**
* For Smaple Notification : SamplecontainerNotificationRecorder
*/
class SamplecontainerNotificationRecorder
{
    public $receiver = [];
    
    public function record($model, $eventName, $entityId)
    {
        if($eventName == 'created'){
            return $this->getData($model, 'create', $entityId);
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
        $data = [
            'version' => getenv('NOTIFICATION_VERSION'),
            'entityId' => $entityId,
            'priority' => 'P2',
            'actor' => $this->getActor(),
            'verb' => 'created',
            'meta' => $model->getMeta(),
            'links' => NULL,
            'createdAt' => Carbon::now()->toDateTimeString(),
            'receiver' => $this->getGroupReceiver()
        ];
        return $data;
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