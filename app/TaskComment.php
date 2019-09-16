<?php

namespace app;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\App\Models\BaseModel;
use Platform\Observers\Tasks\TaskCommentObserver;
// use Platform\App\Activity\ActivityRecorder;

class TaskComment extends BaseModel
{
    use SoftDeletes;

    // use ActivityRecorder;

    protected $appName = 'Task';

    protected $table = 'task_comments';

    public $incrementing = false;

    protected $fillable = [
        'id','task_id','creator_id','type','data',
    ];

    protected $modelVerb = 'comment';

    protected $verb = [];

    public $action = 'comment';

    protected $relation = [
        'creator_id' => 'creator|Platform\Users\Transformers\MetaUserTransformer', 
        'task_id' => 'tasks|Platform\Tasks\Transformers\MetaTaskTransformer', 
    ];

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->data
        ];
    }

    public function getParentMeta()
    {
        return [
            'id' => $this->tasks->id,
            'name'=>$this->tasks->title
        ];
    }


    public function tasks()
    {
        return $this->belongsTo('App\Task', 'task_id');
    }

    public function creator()
    {
    	return $this->belongsTo('App\User', 'creator_id');
    }

    public static function table() {
        $model = new static;
        return $model->getTable();
    }

    public static function boot() {
        $events = new \Illuminate\Events\Dispatcher;
        static::observe(new TaskCommentObserver());
        parent::boot($events);
    }

}
