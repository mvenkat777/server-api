<?php
namespace Platform\App\Activity;

use Illuminate\Support\Facades\Queue;
use App\ActivityModel\NotificationModel\NotifyTarget;
use App\ActivityModel\ObserverModel\CreateObserver;
use App\ActivityModel\ObserverModel\DeleteObserver;
use App\ActivityModel\ObserverModel\UpdateObserver;
use App\User;
use Carbon\Carbon;

abstract class ActivityNotificationFor
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
     * @var objectKey
     */
    protected $objectKey;

    /**
     * @var activityVerb
     */
    protected $activityVerb;

    /**
     * @var action
     */
    protected $action;

    /**
     * create the notification in a defined format
     * @param array $model
     * @return mixed
     */
    protected function notifyTo($model)
    {
        $data = [
                    'localDate' => $this->getLocalTime(),
                    'action' => $this->getAction(),
                    'actionObject' => $this->getActor($model),
                    'verb' => $this->getActivityVerb(),
                    'target' => $this->getObject(),
                ];
        Queue::push(NotifyTarget::create($data));
        return;
    }

    /**
     * @param array $action
     * @return cuurrent instance
     */
    protected function setAction($action){
        $this->action = $action;
        return $this;
    }

    /**
     * @return object instance
     */
    protected function getAction(){
        return $this->action;
    }

    /**
     * @param array $object
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
    protected function getObject()
    {
        return $this->object;
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
     * @return object instance
     */
    protected function getActivityVerb()
    {
        return $this->activityVerb;
    }

    /**
     * @param array $model
     * @return mixed
     */
    protected function getActor($model)
    {
        return [
            'objectType' => 'Person',
            'displayName' => $this->getDisplayName($model),
            'email' => $this->getEmail($model)
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
        } elseif (isset(\Auth::user()->display_name)) {
            return \Auth::user()->display_name;
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
        } elseif (isset(\Auth::user()->email)) {
            return \Auth::user()->email;
        } else {
            return 'NO EMAIL ID';
        }
    }

    /**
     * @return mixed
     */
    protected function getLastCreateActivityInsertedIndexId(){
        return CreateObserver::orderBy('created_at','desc')->limit(1)->get();
    }

    /**
     * @return mixed
     */
    protected function getLastUpdateActivityInsertedIndexId(){
        return UpdateObserver::orderBy('created_at','desc')->limit(1)->get();
    }

    /**
     * @return mixed
     */
    protected function getLastDeleteActivityInsertedIndexId(){
        return DeleteObserver::orderBy('created_at','desc')->limit(1)->get();
    }

    /**
     * @param array $model
     * @return mixed
     */
    public function getUserDetails($model)
    {
        return User::where('id', '=', $model->user_id)->first();
    }

    /**
     * @return current system time in y-m-d format
     */
    public function getLocalTime(){
        return Carbon::now()->format('Y-m-d');
    }
}
