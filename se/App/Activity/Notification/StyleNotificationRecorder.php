<?php
namespace Platform\App\Activity\Notification;

use Carbon\Carbon;
use App\Role;
use App\Group;
use Platform\App\Helpers\Helpers;

/**
* 
*/
class StyleNotificationRecorder
{
    public $receiver = [];
    
    public function record($model, $eventName, $entityId, $content = NULL)
    {
        return $this->getData($model, $eventName, $entityId, $content);
    }

    private function getData($model, $eventName, $entityId, $content)
    {
        $data = [];
        array_push($data, $this->getValues($eventName, 'new', $model, $eventName, $entityId, $content));
        return $data;
    }

    private function getValues($key, $value, $model, $event, $entityId, $content){
        if(is_null($content)){
            $content['links'] = $this->generateContent($model->getDirty());
            $content['verb'] = $event;
            $content['entity']['subEntity'] = ['displayName' => 'Style', 'systemName' => 'style'];
            $content['entity']['subEntity']['meta'] = $model->getMeta();
        }
        $data = [
            'version' => getenv('NOTIFICATION_VERSION'),
            'entityId' => $entityId,
            'priority' => 'P2',
            'actor' => $this->getActor(),
            'verb' => $content['verb'],
            'meta' => $content['entity']['subEntity'],
            'links' => $content['links'],
            'createdAt' => Carbon::now()->toDateTimeString(),
            'receiver' => $this->getReceiver($model)
        ];
        return $data;
    }

    private function generateContent($data){
        $keyAccept = ['name', 'customer_style_code', 'created_at'];
        $link = [];
        foreach ($data as $key => $value) {
            if(in_array($key, $keyAccept)){
                $c = [
                    'fieldName' => Helpers::snakeCaseToCamelCase($key),
                    'originalValue' => NULL,
                    'type' => 'string',
                    'updatedValue' => $value
                ];
                array_push($link, $c);
            }
        }
        return $link;
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

    protected function getReceiver($model)
    {
        $receiver = [$model->line->salesRepresentative->email, $model->line->productDevelopmentLead->email];
        return array_values(array_unique($receiver));
    }
}