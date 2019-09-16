<?php

namespace Platform\App\Activity;

use Carbon\Carbon;
use Platform\App\Activity\Models\GlobalActivity;
use Platform\App\Helpers\Helpers;
use mikemccabe\JsonPatch\JsonPatch;

trait ActivityRecorder
{
    use NotificationRecorder;

    /**
     * Custom Message
     * @var null
     */
    protected $message = NULL;

    public static function bootActivityRecorder()
    {
        foreach(static::getModelEvents() as $event) {
            static::$event(function($model) use($event) {
                try {
                    $model->recordNotification($model, $event);
                    $model->recordActivity($model, $event);
                } catch(\Exception $e){
                }
            });
        }
    }

    public static function getModelEvents()
    {
        if(isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return [
            'created', 'updated', 'deleted'
        ];
    }

    public function recordActivity($model, $event)
    {
        $data = $this->getData($model, $event);
        if (empty($data['links']) && $data['verb'] == 'update') {
        } else {
            GlobalActivity::create($data);
            $activityModel = "\Platform\App\Activity\Models\\".ucfirst($data['entity']['systemName']).'Activity';
            $activityModel::create($data);
        }
    }

    /**
     * Record Custom Activity
     * @param  obj $model
     * @param  array $links
     * @param  string $event
     * @return
     */
    public function recordCustomActivity($model, $links, $event = 'updated')
    {
        $data = $this->getCustomData($model, $links, $event);
        /**
         * Below is for calling custom notification recorder
         */
        $model->recordCustomNotification($model, $data, $event, $this->getActivityName($model));
        GlobalActivity::create($data);
        $activityModel = "\Platform\App\Activity\Models\\".ucfirst($data['entity']['systemName']).'Activity';
        $activityModel::create($data);
    }

    /**
     * Set Custom Message
     * @param string $message
     */
    public function setCustomMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get Custom Message
     * @return string
     */
    public function getCustomMessage()
    {
        return $this->message;
    }

    /**
     * @param   string $model
     * @param   string $event
     * @return  array
     */
    private function getData($model, $event)
    {
        return [
            'version' => 1,
            'objectType' => 'activity',
            'status' => 'hidden',
            'rules' => NULL,
            'entity' => [
                'displayName'=> isset($model->appName)? ucfirst($model->appName) : $this->getActivityName($model),
                'systemName'=> isset($model->appName)? strtolower($model->appName) : strtolower($this->getActivityName($model)),
                'subEntity' => $this->getSubEntity($model),
                'id'=> $this->getAppId($model),
                'meta'=> $this->getActivityParentMeta($model),
                'icon'=> $this->getAppIcon($model),
            ],
            'actor' => $this->getActor(),
            'verb' => $this->getVerb($event, $model),
            'links' => $this->getModifiedFields($model),
            'published'=> Carbon::now()->toDateTimeString()
        ];
    }

    /**
     * @param   string $model
     * @param   string $event
     * @return  array
     */
    private function getCustomData($model, $links, $event='updated')
    {
        return [
            'version' => 1,
            'objectType' => 'activity',
            'status' => 'hidden',
            'rules' => NULL,
            'entity' => [
                'displayName'=> isset($model->appName)? ucfirst($model->appName) : $this->getActivityName($model),
                'systemName'=> isset($model->appName)? strtolower($model->appName) : strtolower($this->getActivityName($model)),
                'subEntity' => $this->getCustomSubEntity($model, $links),
                'id'=> $this->getAppId($model),
                'meta'=> $this->getActivityParentMeta($model),
                'icon'=> $this->getAppIcon($model),
            ],
            'actor' => $this->getActor(),
            'verb' => $this->getVerb($event, $model),
            'links' => $this->getCustomFields($model, $links),
            'published'=> Carbon::now()->toDateTimeString()
        ];
    }

    /**
     * Get Actor For the Activity
     *
     * @return array
     */
    private function getActor()
    {
        if(is_null(\Auth::user())) {
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
                "id"=> \Auth::user()->id,
                "displayName"=> \Auth::user()->display_name,
                "email"=> \Auth::user()->email,
            ]
        ];
    }

    /**
     * get activity name i.e. system_name
     *
     * @param  string $model model instance
     * @return string
     */
    public function getActivityName($model)
    {
        return (new \ReflectionClass($model))->getShortName();
    }

    public function getSubEntity($model)
    {
        $data = [];
        $subEntityName = (new \ReflectionClass($model))->getShortName();
        $subEntityName = preg_replace('/[[T|t]+[N|n]+[A|a]/', 'Calender', $subEntityName);
        if (strtolower($subEntityName) !== $model->appName) {
            $modified = $model->getDirty();
            $dirtyKeys = array_keys($modified);
            $childFields = isset($model->childFields) ? $model->childFields : [];
            $childKeys = array_keys($childFields);
            $comman = array_intersect($dirtyKeys, $childKeys);
            if ($comman) {
                $childEntity = $this->getChildEntity($model, $comman, $modified);
            }

            $response['displayName'] = $subEntityName;
            $response['systemName'] = strtolower($subEntityName);
            // $response['systemName'] = strtolower($subEntityName);
            $response['meta'] = $this->getActivityMeta($model);
            $response['childEntity'] = isset($childEntity)? $childEntity : [];
            return $response;
        }
        return NULL;
    }

    public function getCustomSubEntity($model, $links)
    {
        // $subEntityName = (new \ReflectionClass($model))->getShortName();
        $subEntityName = ucfirst($links[0]);
        $subEntity = [
            'displayName' => $subEntityName,
            'systemName' => strtolower($subEntityName),
            'meta' => isset($links[4]) ? $links[4] : null,
            'childEntity' => []
        ];

        return $subEntity;
    }

    protected function getChildEntity($model, $common, $modified)
    {
        foreach ($common as $key => $one) {
            $childFieldValues = explode('|', $model->childFields[$one]);
            $response['displayName'] = ucfirst($childFieldValues[1]);
            $response['systemName'] = strtolower($childFieldValues[0]);
            $response['meta'] = method_exists($model, $childFieldValues[1]) ?
                    (method_exists($model->$childFieldValues[1], 'getMeta') ?
                    $model->$childFieldValues[1]->getMeta() : NULL)
                    : NULL;
            $data[] = $response;
        }
        return $data;
    }

    /**
     * get url for the recorded activity
     *
     * @param  strin $model model instance
     * @return string
     */
    public function getActivityParentMeta($model)
    {
        if(method_exists($model, 'getParentMeta')) {
            return $model->getParentMeta();
        }
        if (method_exists($model, 'getMeta')) {
            return $model->getMeta();
        }
        return NULL;
    }

    public function getActivityMeta($model)
    {
        if (method_exists($model, 'getMeta')) {
            return $model->getMeta();
        }
        return NULL;
    }

    /**
     * Get App Id
     *
     * @param  string $model model instance
     * @return string
     */
    public function getAppId($model)
    {
        $appName = isset($model->appName)? strtolower($model->appName) : strtolower($this->getActivityName($model));
        return \App\AppsList::where('app_name', $appName)->first()->id;
    }

    /**
     * Get App Icon
     *
     * @param  string $model model instance
     * @return string
     */
    public function getAppIcon($model)
    {
        $appName = isset($model->appName)? strtolower($model->appName) : strtolower($this->getActivityName($model));
        return \App\AppsList::where('app_name', $appName)->first()->icon;
    }

    /**
     * get the modified fields
     *
     * @param  string $model
     * @return array
     */
    public function getModifiedFields($model)
    {
        $message = $this->getCustomMessage();
        if ($message != NULL) {
            $response [] = [
                'type' => 'customMessage',
                'data' => $message
            ];
            return $response;
        }
        $images = isset($model->images)? $model->images : [];
        $document = isset($model->document)? $model->document : [];
        $jsonFields = isset($model->jsonFields)? $model->jsonFields : [];
        $ignore = isset($model->ignore)? $model->ignore : [];

        $fields = $model->getDirty();
        $originalData = $model->getOriginal();
        $data = [];
        $links = [];
        foreach ($fields as $key => $field) {
            $camleCase = Helpers::snakeCaseToCamelCase($key);
            if ($key != 'updated_at' && $key != 'created_at' && !in_array($key, $ignore)) {
                if (in_array($key, $jsonFields) && !empty($originalData)) {
                    if ($model->appName === 'techpack') {
                        $changes = $this->findDiffBom(
                            isset($originalData[$key])? $originalData[$key] : [],
                            $field
                        );
                    }  else{
                        $changes = $this->findDiffStyle(
                            isset($originalData[$key])? $originalData[$key] : [],
                            $field
                        );
                    }
                    foreach ($changes as $change) {
                        if (in_array($change['field'], $images)) {
                            $data['type'] = 'image';
                        } else {
                            $data['type'] = 'event';
                        }
                        $data['fieldName'] = strrev(preg_replace(strrev('/Id/'), '', strrev($change['field']), 1));
                        $data['originalValue'] = ($data['fieldName'] == 'images')? $change['original']['images'] : $change['original'];
                        $data['updatedValue'] = ($data['fieldName'] == 'images')? $change['updated']['images'] : $change['updated'];
                        $links[] = $data;

                    }
                } elseif (in_array($key, $images)) {
                    $data['type'] = 'image';
                    $data['fieldName'] = strrev(preg_replace(strrev('/Id/'), '', strrev($camleCase), 1));
                    $data['originalValue'] = $this->getOriginalValue($model, $key,
                        isset($originalData[$key])? $originalData[$key] : NULL
                    );
                    $data['updatedValue'] = $this->getValue($model, $key, $field);
                    $links[] = $data;
                } elseif (in_array($key, $document)) {
                    $data['type'] = 'document';
                    $data['fieldName'] = strrev(preg_replace(strrev('/Id/'), '', strrev($camleCase), 1));
                    $data['originalValue'] = $this->getOriginalValue($model, $key,
                        isset($originalData[$key])? $originalData[$key] : NULL
                    );
                    $data['updatedValue'] = $this->getValue($model, $key, $field);
                    $links[] = $data;
                } else {
                    $data['type'] = 'event';
                    $data['fieldName'] = strrev(preg_replace(strrev('/Id/'), '', strrev($camleCase), 1));
                    $data['originalValue'] = $this->getOriginalValue($model, $key,
                        isset($originalData[$key])? $originalData[$key] : NULL
                    );
                    $data['updatedValue'] = $this->getValue($model, $key, $field);
                    $links[] = $data;
                }

            }
        }
        return $links;
    }

    public function getCustomFields($model, $links)
    {
        $message = $this->getCustomMessage();
        if ($message != NULL) {
            $response [] = [
                'type' => 'customMessage',
                'data' => $message
            ];
            return $response;
        }
        $relationship = $links[0];
        $data = $links[1];
        $withPivot = $links[2];
        $fetchedData = [];
        $originalValue = isset($links[3]) ? $links[3] : null;

        if(!$withPivot) {
            foreach($data as $value){
                $fetchedData[] = [
                    'type' => 'event',
                    'fieldName' => $relationship,
                    'originalValue' => $originalValue,
                    'updatedValue' => array_values($model->$relationship()->getModel()->find($value)->toArray())[1]
                ];
            }
        } else {
            foreach($data as $key => $value){
                $fetchedData[] = [
                    'type' => 'event',
                    'data' => [
                        'field' => $relationship,
                        'value' => array_values($model->$relationship()->getModel()->find($key)->toArray())[1]
                    ]
                ];
            }
        }
        return $fetchedData;
    }

    public function getVerb($event, $model)
    {
        if ($event == 'created') {
            $verb = isset($model->modelVerb)? $model->modelVerb : '';
            if ($verb == '') {
                return 'create';
            }
            return $verb;
        } elseif ($event == 'updated') {
            $verbs = isset($model->verbs)? $model->verbs : [];
            if ($verbs == []) {
                return 'update';
            }
            return $this->calculateVerb($verbs, $model);
        } elseif ($event == 'deleted') {
            return 'delete';
        }
        return $event;
    }

    public function calculateVerb($verbs, $model)
    {
        $fields = $model->getDirty();
        foreach ($verbs as $key => $verb) {
            if (array_key_exists($key, $fields)) {
                $verb = explode('|', $verb);
                if ($verb[count($verb) - 1] == 'boolean') {
                    if ($fields[$key] != true) {
                        return $verb[1];
                    }
                } elseif ($verb[count($verb) - 1] == 'integer') {
                    return $verb[$fields[$key] - 1];
                }
                return $verb[0];
            }
        }
        return 'update';
    }

    public function getValue($model, $column, $value)
    {
        $relations = isset($model->values) ? $model->values : [];
        $foreign = isset($model->relation) ? $model->relation : [];
        if (array_key_exists($column, $relations)) {
            return $this->calculateValue($relations[$column], $value);
        } elseif (array_key_exists($column, $foreign)) {
            return $this->calculateForeignValue($model, $foreign[$column]);
        }
        return $value;
    }

    public function getOriginalValue($model, $column, $value)
    {
        if ($value == NULL) {
            return $value;
        }
        $relations = isset($model->values) ? $model->values : [];
        $foreign = isset($model->relation) ? $model->relation : [];
        if (array_key_exists($column, $relations)) {
            return $this->calculateValue($relations[$column], $value);
        } elseif (array_key_exists($column, $foreign)) {
            return $this->calculateForeignValue($model, $foreign[$column]);
        }
        return $value;
    }

    public function calculateForeignValue($model, $relation)
    {
        $data = explode('|', $relation);
        if(!is_null($model->$data[0])){
            return (new $data[1])->transform($model->$data[0]);
        }
    }

    public function calculateValue($relativeValues, $value)
    {
        $relativeValues = explode('|', $relativeValues);
        return $relativeValues[$value - 1];
    }


    public function findDiffStyle($original, $updated)
    {

        $differ = new JsonPatch();
        $original = is_string($original)? json_decode($original, true) : $original;
        $updated = is_string($updated)? json_decode($updated, true) : $updated;
        $differences = $differ->diff($original, $updated);

        $r = [];
        foreach ($differences as $diff) {
            $path = explode('/', $diff['path']);
            unset($path[0]);

            $dOriginal = [];
            $dUpdated = [];
                $i = count($path);
                while($i >= 1) {
                    $j = 2;
                    $tempOriginal = $original[$path[1]];
                    $tempUpdated = $updated[$path[1]];
                    while($j <= $i){
                        $tempOriginal = isset($tempOriginal[$path[$j]-1]) ? $tempOriginal[$path[$j]-1] : NULL;
                        $tempUpdated = isset($tempUpdated[$path[$j]-1]) ? $tempUpdated[$path[$j]-1]: $tempUpdated[$path[$j]];
                        $j++;
                    }
                    if($i === count($path)) {
                        $dOriginal[$path[$i]] = $tempOriginal;
                        $dUpdated[$path[$i]] = $tempUpdated;
                    } else{
                        $dOriginal[$path[$i]] = $dOriginal;
                        $dUpdated[$path[$i]] = $dUpdated;
                        unset($dOriginal[$path[$i+1]]);
                        unset($dUpdated[$path[$i+1]]);
                    }
                    $i--;
                }
                $r[] = [
                    'field' => in_array('images', $path)? 'images' : $path[count($path)],
                    'original' => $dOriginal,
                    'updated' => $dUpdated,
                ];
        }
        return $r;
    }

    public function findDiffBOM($original, $updated)
    {

        $differ = new JsonPatch();
        $original = is_string($original)? json_decode($original, true) : $original;
        $updated = is_string($updated)? json_decode($updated, true) : $updated;
        $differences = $differ->diff($original, $updated);

        $r = [];
        foreach ($differences as $diff) {
            $path = explode('/', $diff['path']);
            unset($path[0]);

            $dOriginal = [];
            $dUpdated = [];
                $i = count($path);
                while($i >= 1) {
                    $j = 2;
                    $tempOriginal = $original[$path[1]];
                    $tempUpdated = $updated[$path[1]];
                    while($j <= $i){
                        $tempOriginal = isset($tempOriginal[$path[$j]])? $tempOriginal[$path[$j]]:NULL;
                        $tempUpdated = $tempUpdated[$path[$j]];
                        $j++;
                    }
                    if($i === count($path)) {
                        $dOriginal[$path[$i]] = $tempOriginal;
                        $dUpdated[$path[$i]] = $tempUpdated;
                    } else{
                        $dOriginal[$path[$i]] = $dOriginal;
                        $dUpdated[$path[$i]] = $dUpdated;
                        unset($dOriginal[$path[$i+1]]);
                        unset($dUpdated[$path[$i+1]]);
                    }
                    $i--;
                }
                if (isset($path[count($path)])) {
                    $r[] = [
                        'field' => $path[count($path)],
                        'original' => $dOriginal,
                        'updated' => $dUpdated,
                    ];
                }
        }
        return $r;
    }
}
