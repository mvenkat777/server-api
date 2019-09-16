<?php
namespace Platform\App\Activity\Notification;

use mikemccabe\JsonPatch\JsonPatch;
use Carbon\Carbon;
use App\User;
use App\Techpack;
use App\Role;
use App\Group;

/**
* LineNotificationRecorder
*/
class LineNotificationRecorder
{
    public $receiver = [];
    
    public function record($model, $eventName, $entityId)
    {
        return $this->getData($model, $eventName, $entityId);
    }

    private function getData($model, $eventName, $entityId)
    {
        $data = [];
        array_push($data, $this->getValues($eventName, 'new', $model, $eventName, $entityId));
        return $data;
    }

    private function getValues($key, $value, $model, $event, $entityId){
        $changedData = $this->getChangedData($key, $value, $model, $event, $entityId);
        if(is_null($changedData) || !count($changedData)){
            return NULL;
        }
        if($key == 'created'){
            $key = 'create';
        } elseif($key == 'updated') {
            $key = 'update';
        }
        $data = [
            'version' => getenv('NOTIFICATION_VERSION'),
            'entityId' => $entityId,
            'priority' => 'P2',
            'actor' => $this->getActor(),
            'verb' => $key,
            'displayName'=> isset($model->notificationName)? ucfirst($model->notificationName) : $this->getNotificationName($model),
            'systemName'=> isset($model->notificationName)? strtolower($model->notificationName) : strtolower($this->getNotificationName($model)),
            'meta' => $model->getMeta(),
            'links' => ($event == 'created') ? NULL : $changedData,
            'createdAt' => Carbon::now()->toDateTimeString(),
            'receiver' => $this->getReceiver($model)
        ];
        return $data;
    }

    public function getNotificationName($model)
    {
        return (new \ReflectionClass($model))->getShortName();
    }

    private function getChangedData($keyValue, $value, $model, $event, $entityId)
    {
        $data = [];
        $updatedFields = $model->getDirty();
        $originalFields = $model->getOriginal();
        $blackListKeys = ['updated_at', 'created_at', 'id', 'deleted_at', 'product_brief'];
        
        $diff = $this->findDiff($updatedFields, $originalFields);
        foreach ($diff['difference'] as $key => $value) 
        {
            $val = str_replace('/', '', $value['path']);
            if(isset($value['value']) && in_array($val, $blackListKeys) === false){
                $array = $model->getRelations();
                if(array_key_exists($val, $array)){
                    $arrayData = explode('|', $array[$val]);
                    $rel = $this->checkForRelation($arrayData, $value['value'], isset($diff['oldData'][$key])?$diff['oldData'][$key]:NULL);
                    $val = $rel['fieldName'];
                    $value['value'] = $rel['updated'];
                    $diff['oldData'][$key] = $rel['original'];
                }
                if(isset($value['value']['email']) && isset($value['value']['displayName'])){
                    $value['value'] = $value['value']['displayName'].'('.$value['value']['email'].')';
                }
                if(isset($diff['oldData'][$key]) && isset($diff['oldData'][$key]['displayName']) && isset($diff['oldData'][$key]['email'])){
                    $diff['oldData'][$key] = $diff['oldData'][$key]['displayName'].'('.$diff['oldData'][$key]['email'].')';
                }
                if($val == 'vlp_attachments'){
                    $type = 'image';
                    if(!is_null($value['value']))
                        $value['value'] = json_decode($value['value']);
                    if(isset($diff['oldData'][$key])){
                        $diff['oldData'][$key] = json_decode($diff['oldData'][$key]);
                    }
                } else {
                    $type = 'string';
                }
                $change = [
                    'type' => $type,
                    'fieldName' => $val,
                    'updatedValue' => $value['value'],
                    'originalValue' => isset($diff['oldData'][$key])?$diff['oldData'][$key]:NULL
                ];
                array_push($data, $change);
            }
        }
        return $data;
    }

    public function checkForRelation($data, $updated, $original)
    {
        $content = [];
        $content['fieldName'] =$data[0];
        $updatedUser = User::find($updated);
        $originalUser = User::find($original);
        if($data[0] == 'techpack'){
            $updatedTechpack = Techpack::find($updated);
            $originalTechpack = Techpack::find($original);
            $content['updated'] = (new $data[1])->transform($updatedTechpack);
            $content['original'] = ($original == NULL) ? $original : (new $data[1])->transform($originalTechpack);
            return $content;
        }
        if(!is_null($updatedUser)){
            $content['updated'] = (new $data[1])->transform($updatedUser);   
        } else {
            $content['updated'] = NULL;
        }
        if(!is_null($originalUser)){
            $content['original'] = (new $data[1])->transform($originalUser);
        } else {
            $content['original'] = NULL;
        }
        return $content;
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

    private function findDiff($updated, $original, $key = NULL)
    {
        $updated = is_string($updated)? json_decode($updated, true) : $updated;
        $original = is_string($original)? json_decode($original, true) : $original;
        
        $differ = new JsonPatch();
        $differences = $differ->diff($original, $updated);
        $oldData = [];
        foreach ($differences as $key => $value) {
            $lastData = explode('/', $value['path']);
            $data = $original;
            foreach ($lastData as $key => $value) {
                if($value != NULL || $value != "" ){
                    try{
                       $data = isset($data[$value]) ? $data[$value] : NULL;
                    }catch(\Exception $e){
                        $data = NULL;
                    }
                }
            }
            array_push($oldData, $data);
        }
        return ['difference' => $differences, 'oldData' => $oldData];
    }

    protected function getReceiver($model)
    {
        $receiver = [$model->salesRepresentative->email, $model->productDevelopmentLead->email];
        return array_values(array_unique($receiver));
    }
}