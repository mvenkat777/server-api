<?php
namespace Platform\App\Activity\Notification;

use mikemccabe\JsonPatch\JsonPatch;
use Platform\App\Helpers\Helpers;
use Carbon\Carbon;
use App\Role;
use App\Group;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;

/**
* TechpackNotificationRecorder
*/
class TechpackNotificationRecorder
{
    use DispatchesJobs;

	public function record($model, $eventName, $entityId)
	{
		return $this->getData($model, $eventName, $entityId);
	}

	private function getData($model, $event, $entityId)
	{
		$data = [];
        $keyList = [];
        $originalFields = $model->getDirty();
        if(array_key_exists('poms', $originalFields) || array_key_exists('bill_of_materials', $originalFields)){
            unset($originalFields['data']);
        }
        if($event == 'updated'){
			foreach ($originalFields as $key => $value) {
                $emailNotificationData = [];
                if($key != 'updated_at' && $key != 'data'){
                    $content = $this->getValues($key, $value, $model, $event, $entityId);
                    if(!is_null($content)){
                        array_push($data, $content);
                    }
                }
			}
		} elseif($event == 'created') {
            $diff = \Carbon\Carbon::now()->diffInSeconds(\Carbon\Carbon::now());
            if($diff > 10){
            } else {
                $content = [
                    'version' => getenv('NOTIFICATION_VERSION'),
                    'entityId' => $entityId,
                    'priority' => 'P2',
                    'actor' => $this->getActor(),
                    'verb' => ($event == 'created') ? 'create' : $this->getVerb($key, $model),
                    'meta' => $model->getMeta(),
                    'links' => NULL,
                    'createdAt' => Carbon::now()->toDateTimeString(),
                    'receiver' => $this->getAdditionalReceiver($model, $this->getGroupReceiver(), NULL)
                ];
                array_push($data, $content);
            }
            // $content = $this->getValues('new created', 'new', $model, $event, $entityId);
        }
        return $data;
	}

    private function getValues($key, $value, $model, $event, $entityId){
        $changedData = $this->getChangedData($key, $value, $model);

        if(is_null($changedData) || !count($changedData)){
            return NULL;
        }
        if($changedData[0] == 'BOM Added' || $changedData[0] == 'BOM Removed'){
            return NULL;
            
        }
        $fieldName = ($key == 'new created') ? $model->getAction() : $key;
        $data = [
            'version' => getenv('NOTIFICATION_VERSION'),
            'entityId' => $entityId,
            'priority' => 'P2',
            'actor' => $this->getActor(),
            'verb' => ($event == 'created') ? 'create' : $this->getVerb($key, $model),
            'meta' => $model->getMeta(),
            'links' => $changedData,
            'createdAt' => Carbon::now()->toDateTimeString(),
            'receiver' => $this->getAdditionalReceiver($model, $this->getGroupReceiver(), $changedData),
        ];
        if($changedData[0] == 'BOM Added' || $changedData[0] == 'BOM Removed'){
            // $data['verb'] = $changedData[0];
            // $data['links'] = NULL;

        }
        if($data['links'] != NULL){
            return $data;
        }
        return NULL;
    }

    private function getChangedData($key, $value, $model)
    {
        $data = [];
        $updatedFields = $model->getDirty();
        $originalFields = $model->getOriginal();
        if($key == 'bill_of_materials')
        {
            $diff = $this->findDiff($updatedFields['bill_of_materials'], $originalFields['bill_of_materials']);
            if(!isset($diff['difference'])){
                return $diff;
            }
            foreach ($diff['difference'] as $key => $value) {
                $ifColorName = explode('/', $value['path']);
                $bomName = [0 => 'Fabric', 1 => 'Trims', 2 => 'Art Work', 3 => 'Labels', 4 => 'Wash Finishing', 5 => 'Packing'];
                $type = 'event';
                if(isset($diff['oldData'][$key])&& (is_array($value['value']) || is_array($diff['oldData'][$key]))){
                    $type = 'object';
                }
                if(end($ifColorName) == 'approval'){
                    if(isset($diff['oldData'][$key])){
                        if($diff['oldData'][$key])
                            $original = 'Approved';
                        else
                            $original = 'Unapproved';
                    }
                    if(isset($value['value'])){
                        if($value['value'])
                            $updated = 'Approved';
                        else
                            $updated = 'Unapproved';
                    }
                    if(end($ifColorName) == 'approvedBy' || end($ifColorName) == 'updatedAt'){
                        continue;
                    }
                    $change = [
                        'type' => $type,
                        'fieldName' => 'billOfMaterials/'.$bomName[$ifColorName[1]].'/'.end($ifColorName).'('.$ifColorName[5].')',
                        'updatedValue' => isset($updated)?$updated:NULL,
                        'originalValue' => isset($original)?$original:NULL
                    ];
                } else {
                    $change = [
                        'type' => $type,
                        'fieldName' => 'billOfMaterials/'.$bomName[$ifColorName[1]].'/'.end($ifColorName),
                        'updatedValue' => isset($value['value'])?$value['value']:NULL,
                        'originalValue' => isset($diff['oldData'][$key])?$diff['oldData'][$key]:NULL
                    ];
                }
                array_push($data, $change);
            }
            return $data;
        } elseif($key == 'poms'){
            $diff = $this->findDiff($updatedFields['data'], $originalFields['data'], $key);
            if(!isset($diff['difference'])){
                return $diff;
            }
            foreach ($diff['difference'] as $key => $value) {
                $selectKey = ['pomCode', 'description'];
                $ifColorName = explode('/', $value['path']);
                if(in_array(end($ifColorName), $selectKey)){

                    $change = [
                        'type' => 'event',
                        'fieldName' => 'pom/'.end($ifColorName),
                        'updatedValue' => isset($value['value'])?$value['value']:NULL,
                        'originalValue' => isset($diff['oldData'][$key])?$diff['oldData'][$key]:NULL
                    ];
                    array_push($data, $change);
                }
            }
            return $data;
        }
        elseif($key == 'colorway') {
            $diff = $this->findDiff($updatedFields['colorway'], $originalFields['colorway']);
            if(!isset($diff['difference'])){
                return $diff;
            }
            foreach ($diff['difference'] as $key => $value) {
                if(isset($value['value']) && !is_object($value['value'])){
                    $ifColorName = explode('/', $value['path']);
                    if(isset($ifColorName['3']) &&  $ifColorName['3'] == 'color_name'){
                        $change = [
                            'type' => 'event',
                            'fieldName' => 'billOfMaterials/colorway/'.$ifColorName[1],
                            'updatedValue' => isset($value['value'])?$value['value']:NULL,
                            'originalValue' => isset($diff['oldData'][$key])?$diff['oldData'][$key]:NULL
                        ];
                        if($this->isToBePushed($change))
                            array_push($data, $change);
                    } elseif(is_array($value['value'])){
                        $change = [
                            'type' => 'event',
                            'fieldName' => 'billOfMaterials/colorway/'.$ifColorName[1],
                            'updatedValue' => isset($value['value']['color_name'])?$value['value']['color_name']:NULL,
                            'originalValue' => NULL
                        ];
                        if($this->isToBePushed($change))
                            array_push($data, $change);
                    } elseif(isset($ifColorName['3']) &&  $ifColorName['3'] == 'approval'){
                        if(isset($diff['oldData'][$key])){
                            if($diff['oldData'][$key])
                                $original = 'Approved';
                            else
                                $original = 'Unapproved';
                        }
                        if(isset($value['value'])){
                            if($value['value'])
                                $updated = 'Approved';
                            else
                                $updated = 'Unapproved';
                        }
                        $change = [
                            'type' => 'event',
                            'fieldName' => 'billOfMaterials/colorway/'.$ifColorName[1].'(approval)',
                            'updatedValue' => isset($updated)?$updated:NULL,
                            'originalValue' => isset($original)?$original:NULL
                        ];
                        if($this->isToBePushed($change))
                            array_push($data, $change);
                    }
                }
            }
            return $data;
        } elseif($key == 'stage' || $key == 'season' || $key == 'size_type') {
            $change = [
                'type' => 'event',
                'fieldName' => $this->snakeCaseToCamelCase($key),
                'updatedValue' => $updatedFields[$key],
                'originalValue' => $originalFields[$key]
            ];
            if($this->isToBePushed($change))
                array_push($data, $change);
            return $data;
        } elseif($key == 'image') {
            $updatedValue = isset($updatedFields['image'])?json_decode($updatedFields['image']):NULL;
            if(count($updatedValue) == 0){
                $updatedValue = NULL;
            }
            $originalValue = isset($originalFields['image'])?json_decode($originalFields['image']):NULL;
            if(count($originalValue) == 0){
                $originalValue = NULL;
            }
            $change = [
                'type' => 'image',
                'fieldName' => $key,
                'updatedValue' => $updatedValue,
                'originalValue' => $originalValue
            ];
            if($this->isToBePushed($change))
                array_push($data, $change);
            return $data;
        } elseif($key == 'visibility') {
            $change = [
                'type' => 'event',
                'fieldName' => $key,
                'updatedValue' => ($updatedFields['visibility'] == false)?'Private': 'Public',
                'originalValue' => ($originalFields['visibility'] == false)?'Private': 'Public'
            ];
            if($this->isToBePushed($change))
                array_push($data, $change);
            return $data;
        }
    }

    public function isToBePushed($data)
    {
        if(!is_null($data['updatedValue']) || !is_null($data['originalValue'])){
            return true;
        } else {
            return false;
        }
    }

    private function getVerb($key, $model)
    {
        if(is_null($model->getVerbs())) {
            if($model->getModelVerbs() == 'add'){
                return 'updated';
            } else {
                return $model->getModelVerbs();
            }
        }
        if(array_key_exists($key, $model->getVerbs()))
        {
            if($key == 'assignee_id' || $key == 'seen'){
                return $model->getVerbs()[$key];
            } elseif($key == 'is_submitted'){
                return 'submitted or not';
            } elseif($key == 'status_id'){
                return 'status check';
            }
            return $key;
        }
        return 'update';
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
        if($key == 'pom'){
            $updated = $updated['poms'];
            $original = $original['poms'];
        }
        $totalUpdatedBOM = count($updated);
        $totalOriginalBOM = count($original);
        if($totalOriginalBOM > $totalUpdatedBOM){
            return ['BOM Removed'];
        } elseif($totalOriginalBOM < $totalUpdatedBOM) {
            return ['BOM Added'];
        } else {
            $differ = new JsonPatch();
            $differences = $differ->diff($original, $updated);
            $oldData = [];
            $operation = [];
            foreach ($differences as $key => $value) {
                if($value['op'] == 'add'){
                    array_push($operation, 'add');
                } else {
                    if(in_array('add', $operation) === true){
                        unset($differences[$key]);
                        $differences = array_values($differences);
                        continue;
                    }
                }
                $lastData = explode('/', $value['path']);
                $blacklistKey = ['changeLog', 'data', 'updatedValue', 'updatedat ', 'pathway', 'updatedAt', 'fieldName', 'revision', 'meta', 'id', 'bomLineItemId', 'createdAt'];
                $check = array_intersect($blacklistKey, $lastData);
                if(!empty($check)){
                    unset($differences[$key]);
                    $differences = array_values($differences);
                    continue;
                }
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
	}

    protected function getGroupReceiver()
    {
        $group = Group::where('name', 'Techpack')->first();
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

    protected function getAdditionalReceiver($model, $existingReceivers, $change)
    {
        if(!is_null($model->style)){
            foreach ($change as $key => $value) {
                if (strpos($value['fieldName'], 'approval') !== false) {
                    $rec = [$this->getUser($model->style->line->sales_representative_id)->email, $this->getUser($model->style->line->production_lead_id)->email];
                    array_values(array_unique($rec));
                    return $rec;                
                }
            }
            // foreach ($change as $key => $value) {
            //     if (strpos($value['fieldName'], 'billOfMaterials') !== false || strpos($value['fieldName'], 'poms') !== false) {
            //         return $existingReceivers;                
            //     }
            // }
         array_push($existingReceivers, $this->getUser($model->style->line->sales_representative_id)->email, $this->getUser($model->style->line->production_lead_id)->email);
        } else{
            return $existingReceivers;
        }
        return array_values(array_unique($existingReceivers));
    }

    private function getUser($find){
        return \App\User::where('email', $find)->orWhere('id', $find)->first();
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
}