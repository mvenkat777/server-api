<?php
namespace Platform\App\Activity;

use Illuminate\Support\Facades\Queue;
use App\ActivityModel\ObserverModel\TaskActivityObserver;
use App\ActivityModel\ObserverModel\TNAActivityObserver;
use App\ActivityModel\ObserverModel\SampleActivityObserver;
use App\ActivityModel\ObserverModel\TechpackActivityObserver;
use App\User;
use App\Task;
use Carbon\Carbon;

abstract class ActivityObserver
{
    /**
     * array varaible declared
     * @var data
     */
    public $data = [];

    /**
     * @var object
     */
    protected $object;

    /**
     * @var objectType
     */
    protected $objectType;


    /**
     * @var activityVerb
     */
    protected $activityVerb;

    /**
     * create the observer in a defined format
     * @param array $model
     * @return mixed
     */
    protected function create($model, $category)
    {
        try{
            $getObject = json_decode(json_encode($this->getObject()));
        }
        catch(Exception $e){
            $getObject = $this->getObject();
        }
        try{
            $data = [
                    'localDate' => $this->getLocalTime(),
                    'actor' => $this->getActor($model),
                    'message' => $this->generateMessage($this->getActivityVerb(), $this->getActor($model)['displayName'], $getObject, $category, $model)
                ];
            if($category == 'task'){
                if(isset($getObject->task->taskId)){
                        $data['id'] = $getObject->task->taskId;
                } else{
                        $data['id'] = $getObject->task->id;
                }

                if(empty($data['message'])) {
                    return;
                }

                TaskActivityObserver::create($data);
            } elseif($category == 'tna') {
                if(isset($getObject->tna->tnaId)){
                    $data['id'] = $getObject->tna->tnaId;
                } else{
                    $data['id'] = $getObject->tna->id;
                }
                TNAActivityObserver::create($data);
            } elseif($category == 'sampleSubmission' || $category == 'sampleSubmissionCategory' || $category == 'sampleSubmissionComment' || $category == 'sampleSubmissionAttachment') {
                if(isset($getObject->sampleSubmission->sample_submission_id)){
                        $data['id'] = $getObject->sample->sample_submission_id;
                } elseif(isset($getObject->sampleSubmissionCategory->sampleId)){
                    $data['id'] = $getObject->sampleSubmissionCategory->sampleId;
                } elseif(isset($model->sample_submission_id)){
                    $data['id'] = $model->sample_submission_id;
                } else{
                    $data['id'] = $getObject->sampleSubmission->id;
                }
                SampleActivityObserver::create($data);
            } elseif($category == 'techpack'){
                if(isset($getObject->techpack->techpackId)){
                    $data['id'] = $getObject->techpack->techpackId;
                } else{
                    $data['id'] = $getObject->techpack->id;
                }
                TechpackActivityObserver::create($data);
            } else{
                return;
            }
        } 
        catch(\Exception $e) {
            return;
        }
    }

    /**
     * insert the update key of the observer in a defined format
     * @param array $model
     * @return mixed
     */
    protected function update($model, $category)
    {
        try{
            $getObject = json_decode(json_encode($this->getObject()));
        }
        catch(Exception $e){
            $getObject = $this->getObject();
        }
        try {
                if($this->generateMessage($this->getActivityVerb(), $this->getActor($model)['displayName'], $getObject, $category, $model) == NULL){
                    return;
                }
                $data = [
                        'localDate' => $this->getLocalTime(),
                        'actor' => $this->getActor($model),
                        'message' => $this->generateMessage($this->getActivityVerb(), $this->getActor($model)['displayName'], $getObject, $category, $model)
                    ];
                if($category == 'task'){
                    if(isset($getObject->task->taskId)){
                        $data['id'] = $getObject->task->taskId;
                    } else{
                        $data['id'] = $getObject->task->id;
                    }

                    if(empty($data['message'])) {
                        return;
                    }

                    TaskActivityObserver::create($data);
                } elseif($category == 'tna') {
                    if(isset($getObject->tna->tnaId)){
                        $data['id'] = $getObject->tna->tnaId;
                    } else{
                        $data['id'] = $getObject->tna->id;
                    }
                    TNAActivityObserver::create($data);
                } elseif($category == 'sampleSubmission' || $category == 'sampleSubmissionCategory' || $category == 'sampleSubmissionComment' || $category == 'sampleSubmissionAttachment') {
                    if(isset($getObject->sampleSubmission->sample_submission_id)){
                        $data['id'] = $getObject->sample->sample_submission_id;
                    } elseif(isset($getObject->sampleSubmissionCategory->sampleId)){
                        $data['id'] = $getObject->sampleSubmissionCategory->sampleId;
                    } elseif(isset($model->sample_submission_id)){
                        $data['id'] = $model->sample_submission_id; 
                    } else{
                        $data['id'] = $getObject->sampleSubmission->id;
                    }
                    SampleActivityObserver::create($data);
                } elseif($category == 'techpack'){
                    if(isset($getObject->techpack->techpackId)){
                        $data['id'] = $getObject->techpack->techpackId;
                    } else{
                        $data['id'] = $getObject->techpack->id;
                    }
                    TechpackActivityObserver::create($data);
                } else{
                    return;
                }
        } catch (\Exception $e) {
            return;
        }
    }

    /**
     * insert the delete key of the observer in a defined format
     * @param array $model
     * @return mixed
     */
    protected function delete($model, $category)
    {
        try{
            $getObject = json_decode(json_encode($this->getObject()));
        }
        catch(Exception $e){
            $getObject = $this->getObject();
        }
        try {
                if($this->generateMessage($this->getActivityVerb(), $this->getActor($model)['displayName'], $getObject, $category, $model) == NULL){
                    return;
                }
                $data = [
                        'localDate' => $this->getLocalTime(),
                        'actor' => $this->getActor($model),
                        'message' => $this->generateMessage($this->getActivityVerb(), $this->getActor($model)['displayName'], $getObject, $category, $model)
                    ];
                if($category == 'task'){
                    if(isset($getObject->task->taskId)){
                        $data['id'] = $getObject->task->taskId;
                    } else{
                        $data['id'] = $getObject->task->id;
                    }
                    TaskActivityObserver::create($data);
                } elseif($category == 'tna') {
                if(isset($getObject->tna->tnaId)){
                    $data['id'] = $getObject->tna->tnaId;
                } else{
                    $data['id'] = $getObject->tna->id;
                }
                TNAActivityObserver::create($data);
                } elseif($category == 'sampleSubmission' || $category == 'sampleSubmissionCategory' || $category == 'sampleSubmissionComment' || $category == 'sampleSubmissionAttachment') {
                    if(isset($getObject->sampleSubmission->sample_submission_id)){
                        $data['id'] = $getObject->sample->sample_submission_id;
                    } elseif(isset($getObject->sampleSubmissionCategory->sampleId)){
                        $data['id'] = $getObject->sampleSubmissionCategory->sampleId;
                    } elseif(isset($model->sample_submission_id)){
                        $data['id'] = $model->sample_submission_id; 
                    } else{
                        $data['id'] = $getObject->sampleSubmission->id;
                    }
                    SampleActivityObserver::create($data);
                } elseif($category == 'techpack'){
                    if(isset($getObject->techpack->techpackId)){
                        $data['id'] = $getObject->techpack->techpackId;
                    } else{
                        $data['id'] = $getObject->techpack->id;
                    }
                    TechpackActivityObserver::create($data);
                } else{
                    return;
                }
        } catch (\Exception $e) {
            // dd($e);
            return;
        }
    }

    /**
     * @return object instance
     */
    protected function getObject()
    {
        return $this->object;
    }

    /**
     * @param $object
     * @return current instance
     */
    protected function setObject($object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @return object instance
     */
    protected function getObjectType()
    {
        return $this->objectType;
    }

    /**
     * @param $objectType
     * @return current instance
     */
    protected function setObjectType($objectType)
    {
        $this->objectType = $objectType;
        return $this;
    }

    /**
     * @return object instance
     */
    protected function getActivityVerb()
    {
        return $this->activityVerb;
    }

    /**
     * @param $activityVerb
     * @return current instance
     */
    protected function setActivityVerb($activityVerb)
    {
        $this->activityVerb = $activityVerb;
        return $this;
    }

    /**
     * @param array $model
     * @return mixed
     */
    protected function getActor($model)
    {
        return [
            'objectType' => 'User',
            'displayName' => isset(\Auth::user()->display_name) ? \Auth::user()->display_name : 'Cron User',
            'email' => isset(\Auth::user()->email) ? \Auth::user()->email : 'sedev@sourceeasy.com'
        ];
    }

    /**
     * @param array $model
     * @return mixed
     */
    protected function getDisplayName($model)
    {
        if (isset($model->display_name)) {
            return $model->display_name;
        } elseif (isset($this->getUserDetails($model)->display_name)) {
            return $this->getUserDetails($model)->display_name;
        } elseif (isset(json_decode($model->creator)->display_name)) {
            return json_decode($model->creator)->display_name;
        } elseif (isset(json_decode($model->owner_details)->display_name)) {
            return json_decode($model->owner_details)->display_name;
        } elseif (isset($model->sender_name)) {
            return $model->sender_name;
        } else {
            return 'NO DISPLAY NAME';
        }
    }

    /**
     * @param array $model
     * @return mixed
     */
    protected function getEmail($model)
    {
        if (isset($model->email)) {
            return $model->email;
        } elseif (isset($this->getUserDetails($model)->email)) {
            return $this->getUserDetails($model)->email;
        } elseif (isset(json_decode($model->creator)->email)) {
            return json_decode($model->creator)->email;
        } elseif (isset(json_decode($model->owner_details)->email)) {
            return json_decode($model->owner_details)->email;
        } elseif (isset($model->sender_email)) {
            return $model->sender_email;
        } else {
            return 'NO EMAIL ID';
        }
    }

    /**
     * @param array $model
     * @return mixed
     */
    public function getUserDetails($model)
    {
        return User::where('id', '=', $model->creator)->first();
    }

    /**
     * @param $taskId
     * @return mixed
     */
    public function getTaskDetails($taskId)
    {
        return Task::where('id', '=', $taskId)->first();
    }

    /**
     * @return current system time in y-m-d format
     */
    public function getLocalTime(){
        return Carbon::now()->toDateTimeString();
    }

    public function generateMessage($verb, $actor, $object, $category, $model){
        if($verb == 'created'){
            if($category == 'task'){
                return $this->generateTaskMessage($verb, $actor, $object, $category, $model);
            } elseif($category == 'tna') {
                return $this->generateTNAMessage($verb, $actor, $object, $category, $model);
            } elseif($category == 'sampleSubmission' || $category == 'sampleSubmissionCategory' || $category == 'sampleSubmissionComment' || $category == 'sampleSubmissionAttachment') {
                return $this->generateSampleSubmissionMessage($verb, $actor, $object, $category, $model);
            } elseif($category == 'techpack') {
                return $this->generateTechpackMessage($verb, $actor, $object, $category, $model);
            }
        } elseif($verb == 'updated') {
            if($category == 'task'){
                return $this->generateTaskMessage($verb, $actor, $object, $category, $model);
            } elseif($category == 'tna') {
                return $this->generateTNAMessage($verb, $actor, $object, $category, $model);
            } elseif($category == 'sampleSubmission' || $category == 'sampleSubmissionCategory' || $category == 'sampleSubmissionComment' || $category == 'sampleSubmissionAttachment') {
                return $this->generateSampleSubmissionMessage($verb, $actor, $object, $category, $model);
            } elseif($category == 'techpack') {
                return $this->generateTechpackMessage($verb, $actor, $object, $category, $model);
            }
        } elseif($verb == 'deleted') {
            if($category == 'task'){
                return $this->generateTaskMessage($verb, $actor, $object, $category, $model);
            } elseif($category == 'tna') {
                return $this->generateTNAMessage($verb, $actor, $object, $category, $model);
            } elseif($category == 'sampleSubmission' || $category == 'sampleSubmissionCategory' || $category == 'sampleSubmissionComment' || $category == 'sampleSubmissionAttachment') {
                return $this->generateSampleSubmissionMessage($verb, $actor, $object, $category, $model);
            } elseif($category == 'techpack') {
                return $this->generateTechpackMessage($verb, $actor, $object, $category, $model);
            }
        } else{
            return;
        }
    }

    public function generateTaskMessage($verb, $actor, $object, $category, $model){
        if($object->objectType == 'created'){
            return $actor.' created a task and assigned it to - "'.$object->task->assignee->displayName.'"';
        } elseif($object->objectType == 'title') {
            return $actor.' updated task title to - "'.$object->task->title.'"';
        } elseif($object->objectType == 'description') {
            return $actor.' updated task description';
        } elseif($object->objectType == 'assignee'){
            return $actor.' reassigned task to - "'.$object->task->assignee->displayName.'"';
        } elseif($object->objectType == 'dueDate') {
            return $actor.' updated task due date to - "'.Carbon::parse($object->task->dueDate)->format('m-d-y').'"';
        } elseif($object->objectType == 'seen') {
            return $object->task->assignee->displayName.' has seen this task';
        } elseif($object->objectType == 'completionDate') {
            return $actor.' updated task completion date to - "'.Carbon::parse($model->completion_date)->format('m-d-y').'"';
        } elseif($object->objectType == 'priority') {
            return $actor.' updated task priority to - "'.$object->task->priority->PriorityName.'"';
        } elseif($object->objectType == 'status') {
            return $actor.' updated task status to - "'.$object->task->status.'"';
        } elseif($object->objectType == 'attachement added'){
            return $actor.' uploaded an attachment';
        } elseif($object->objectType == 'commented'){

            return $actor.' commented on the task - "'.$model->data.'"';
        } elseif($object->objectType == 'follower') {
            return $object->message;
        } /*else {
            return $actor.' updated task category to - "'.$object->task->categories[0]['title'].'"';   
        }*/
        return;
    }

    public function generateTNAMessage($verb, $actor, $object, $category, $model) {
        if($object->objectType == 'created'){
            return $object->tna->creator->displayName.' created a new TNA whose representer is '.$object->tna->representor->displayName;
        } elseif($object->objectType == 'title') {
            return $actor.' updated tna title to - "'.$object->tna->title.'"';
        } elseif($object->objectType == 'state'){
            return $actor.' updated tna state to - "'.$object->tna->state.'"';
        } elseif($object->objectType == 'startDate') {
            return $actor.' updated start date to - "'.Carbon::parse($object->tna->startDate)->format('m-d-y').'"';
        } elseif($object->objectType == 'targetDate') {
            return $actor.' updated target date to - "'.Carbon::parse($object->tna->targetDate)->format('m-d-y').'"';
        } elseif($object->objectType == 'isPublished') {
            return $actor.' set tna publish status to - "'.$object->tna->isPublished.'"';
        } elseif($object->objectType == 'publishedDate') {
            return $actor.' updated publish date to - "'.Carbon::parse($object->tna->publishedDate)->format('m-d-y').'"';
        } 
            // elseif($object->objectType == 'projectedDate') {
        //     return $actor.' updated project date to - "'.$object->tna->projectedDate.'"';
        // } 
        elseif($object->objectType == 'completedDate'){
            return $actor.' updated completion date to - "'.Carbon::parse($object->tna->completedDate)->format('m-d-y').'"';
        } elseif($object->objectType == 'customerName'){
            return $actor.' updated customer name - "'.$object->tna->customerName.'"';
        } elseif($object->objectType == 'customerCode') {
            return $actor.' updated customer code - "'.$object->tna->customerCode.'"';
        } elseif($object->objectType == 'styleDescription') {
            return $actor.' updated style description - "'.$object->tna->styleDescription.'"';
        } elseif($object->objectType == 'customerCode') {
            return $actor.' updated customer code - "'.$object->tna->customerCode.'"';
        } elseif($object->objectType == 'tnaHealth') {
            return $actor.' updated tna health state to "'.$object->tna->tnaHealth.'"';
        } elseif($object->objectType == 'itemsOrder') {
            return $actor.' items order has been updated';
        } elseif($object->objectType == 'attachement') {
            return $actor.' uploaded an attachement';
        } else {
            return ;   
        }
    }

    public function generateSampleSubmissionMessage($verb, $actor, $object, $category, $model){
        if($object->objectType == 'created'){
            return $actor.' created a sample';
        } elseif($object->objectType == 'name') {
            return $actor.' updated sample name to - "'.$object->sampleSubmission->name.'"';
        } elseif($object->objectType == 'styleCode') {
            return $actor.' updated sample style code - "'.$object->sampleSubmission->styleCode.'"';
        } elseif($object->objectType == 'sentDate'){
            return $actor.' updated sample sent date - "'.$object->sampleSubmission->sentDate.'"';
        } elseif($object->objectType == 'receivedDate') {
            return $actor.' updated sample receive date to - "'.$object->sampleSubmission->receivedDate.'"';
        } elseif($object->objectType == 'type') {
            return $actor.' updated sample type - "'.$object->sampleSubmission->type.'"';
        } elseif($object->objectType == 'content') {
            if(empty($object->sampleSubmission)){
                return $actor.' updated sample content to - "'.$object->sampleSubmissionCategory->content.'"';
            }
            return $actor.' updated sample content to - "'.$object->sampleSubmission->content.'"';
        } elseif($object->objectType == 'weight') {
            return $actor.' updated weight to - "'.$object->sampleSubmission->weight.'"';
        } elseif($object->objectType == 'vendor') {
            return $actor.' changed vendor to - "'.$object->sampleSubmission->vendor.'"';
        } elseif($object->objectType == 'customer'){
            return $actor.' updated customer -"'.$object->sampleSubmission->customer->name;
        } elseif($object->objectType == 'techpack'){
            return $actor.' updated techpack - "'.$object->sampleSubmission->techpack->name.'"';
        } elseif($object->objectType == 'category created') {
            return $actor.' created a category - "'.$object->sampleSubmissionCategory->name;
        } elseif($object->objectType == 'content') {
            return $actor.' updated content to - "'.$object->sampleSubmissionCategory->content;
        } elseif($object->objectType == 'comment added') {
            return $actor.' commented on this sample - "'.$object->sampleSubmissionComment->comment;
        } elseif($object->objectType == 'file' || $object->objectType == 'uploadedBy' ) {
            return $actor.' uploaded an attachement';
        } 
        return;
    }

    public function generateTechpackMessage($verb, $actor, $object, $category, $model) {

        if($object->objectType == 'created'){
            return $actor.' created a new Techpack';
        } elseif($object->objectType == 'version') {
            return $actor.' has set version to - "'.$object->techpack->version.'"';
        } elseif($object->objectType == 'name'){
            return $actor.' updated techpack name to - "'.$object->techpack->name.'"';
        } elseif($object->objectType == 'styleCode') {
            return $actor.' updated style code to - "'.$object->techpack->styleCode.'"';
        } elseif($object->objectType == 'category') {
            return $actor.' updated category as - "'.$object->techpack->category.'"';
        } elseif($object->objectType == 'season') {
            return $actor.' updated season as - "'.$object->techpack->season.'"';
        } elseif($object->objectType == 'stage') {
            return $actor.' updated stage as - "'.$object->techpack->stage.'"';
        } elseif($object->objectType == 'visibility') {
            return $actor.' updated techpack visibility';
        } elseif($object->objectType == 'image'){
            return $actor.' uploaded an image';
        } elseif($object->objectType == 'revision'){
            return $actor.' updated revision';
        } elseif($object->objectType == 'meta') {
            return $actor.' updated meta field';
        } elseif($object->objectType == 'billOfMaterials') {
            return $actor.' updated bill of materials';
        } elseif($object->objectType == 'commented') {
            return $actor.' commented on Techpack';
        }elseif($object->objectType == 'lockedAt' && !is_null($object->techpack->lockedAt)) {
            return $actor.' Locked Techpack at '. Carbon::parse($object->techpack->lockedAt)->toDateTimeString();
        }elseif($object->objectType == 'unlockedAt' && !is_null($object->techpack->unlockedAt)) {
            return $actor.' unlocked Techpack at '. Carbon::parse($object->techpack->unlockedAt)->toDateTimeString();
        } else {
            return ;   
        }   
    }
}
