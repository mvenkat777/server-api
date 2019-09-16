<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Platform\App\Activity\ActivityRecorder;

class TaskAttachment extends Model
{
    use ActivityRecorder;

    protected $table = 'task_attachments';

    public $incrementing = false;

    protected $fillable = ['id', 'task_id', 'creator_id', 'type', 'data'];

    protected $appName = 'task';

    protected $modelVerb = 'attach';

    protected $images = ['data'];

    protected $relation = [
        'creator_id' => 'creator|Platform\Users\Transformers\MetaUserTransformer', 
        'task_id' => 'tasks|Platform\Tasks\Transformers\MetaTaskTransformer', 
    ];

    protected $verbs = [];

    protected $image = ['data'];

    public function getMeta()
    {
        return ['id' => $this->id,
            'name'=>$this->type
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
        static::observe(new \Platform\Observers\Tasks\TaskAttachementObserver);
        parent::boot($events);
    }
}
