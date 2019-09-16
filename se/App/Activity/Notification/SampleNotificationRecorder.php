<?php
namespace Platform\App\Activity\Notification;

use Carbon\Carbon;
use App\Role;
use App\Group;

/**
* For Smaple Notification : SamplecontainerNotificationRecorder
*/
class SampleNotificationRecorder
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
        $val = $this->getValues($eventName, 'new', $model, $eventName, $entityId);
        if(count($val))
            array_push($data, $this->getValues($eventName, 'new', $model, $eventName, $entityId));
        return $data;
    }

    private function getValues($key, $value, $model, $event, $entityId){
        $actor = $this->getActor();
        if(!$actor){
            return [];
        }
        $data = [
            'version' => getenv('NOTIFICATION_VERSION'),
            'entityId' => $entityId,
            'priority' => 'P2',
            'actor' => $this->getActor(),
            'verb' => 'comment',
            'meta' => $model->getParentMeta(),
            'links' => $this->getLinks($model),
            'createdAt' => Carbon::now()->toDateTimeString(),
            'receiver' => $this->getGroupReceiver($model)
        ];
        var_dump($data);
        return $data;
    }

    private function getActor()
    {
        $user = \Auth::user();
        $isCustomer = \App\User::where('id', $user->id)->where('se', false)->first();
        if(is_null($isCustomer)){
            return false;
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

    protected function getGroupReceiver($model)
    {
        $sampleContainer = $model->sample->sampleContainer;
        if(!is_null($sampleContainer->style)){
            return [$this->getUser($sampleContainer->style->line->sales_representative_id)->email, $this->getUser($sampleContainer->style->line->production_lead_id)->email];
        }
        return [];
    }

    protected function getLinks($model)
    {
        $updatedValue = $model->getDirty();
        $change = [
            'type' => 'string',
            'fieldName' => str_replace(' ', '', $updatedValue['criteria']),
            'updatedValue' => $updatedValue['description'],
            'originalValue' => NULL
        ];
        return $change;
    }

    protected function getUser($user)
    {
        return \App\User::where('id', $user)->first();
    }
}