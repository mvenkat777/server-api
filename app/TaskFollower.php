<?php

namespace app;

// use Illuminate\Database\Eloquent\Model;
use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\Observers\Tasks\TaskFollowerObserver;
// use Platform\App\Activity\ActivityRecorder;

class TaskFollower extends BaseModel
{
    use SoftDeletes;

    // use ActivityRecorder;

    protected $appName = 'Task';

    protected $table = 'task_followers';

    public $incrementing = false;

    protected $fillable = [
        'id','task_id','follower_id'
    ];

    protected $modelVerb = 'add';

    protected $action = 'follower';

    protected $relation = [
        'follower_id' => 'user|Platform\Users\Transformers\MetaUserTransformer', 
        'task_id' => 'tasks|Platform\Tasks\Transformers\MetaTaskTransformer', 
    ];

    public function getMeta()
    {
        return ['id' => $this->id,
            'name'=>$this->user->display_name,
            'email' => $this->user->email
        ];
    }

    public function getParentMeta()
    {
        return ['id' => $this->tasks->id,
            'name'=>$this->tasks->title
        ];
    }

    public function tasks()
    {
        return $this->belongsTo('App\Task', 'task_id');
    }

    public function user()
    {
    	return $this->belongsTo('App\User', 'follower_id');
    }

    public static function table() {
        $model = new static;
        return $model->getTable();
    }

    public static function boot() {
        $events = new \Illuminate\Events\Dispatcher;
        static::observe(new TaskFollowerObserver());
        parent::boot($events);
    }

}
