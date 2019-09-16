<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\Tasks\Helpers\TaskHelper;
// use Platform\App\Activity\ActivityRecorder;

class Task extends BaseModel
{
    use SoftDeletes;

    // use ActivityRecorder;

    protected $table = 'tasks';

    public $appName = 'task';
    
    public $incrementing = false;

    protected $action = 'created';

    protected $fillable = [
        'id','creator_id','assignee_id','title','description','due_date','priority_id',
        'creator_id','status_id', 'seen', 'is_submitted', 'submission_date',
        'assignee_status', 'snoozed_time', 'location', 'is_completed', 'completion_date', 
        'tna_item_id', 'archived_at', 'google_calendar_id'
    ];
    public $globalSearchColumns = ['title', 'description'];

    public $relation = ['creator_id' => 'creator|Platform\Users\Transformers\MetaUserTransformer', 
        'assignee_id' => 'assignee|Platform\Users\Transformers\MetaUserTransformer',
        'tna_item_id' => 'tnaItem|Platform\TNA\Transformers\MetaTNAItemTransformer',
    ];

    public $verbs = [
        'is_submitted' => 'submit|reject|boolean',
        'status_id' => 'unassign|assign|start|submit|complete|integer',
        'assignee_id' => 'reassign',
        'seen' => 'read',
        'priority_id' => 'low|highest|intermediate'
    ];


    public $values = [
        'priority_id' => 'low|highest|intermediate',
        'status_id' => 'unassigned|assigned|started|submitted|completed'
    ];

    public function getMeta()
    {
        return ['id' => $this->id,
            'name'=>$this->title
        ];
    }

    public function getDateAttribute($value)
    {
        $this->attributes['created_at'] = Carbon\Carbon::createFromFormat('d-m-Y h:i', $value);
        $this->attributes['updated_at'] = Carbon\Carbon::createFromFormat('d-m-Y h:i', $value);
        $this->attributes['deleted_at'] = Carbon\Carbon::createFromFormat('d-m-Y h:i', $value);
    }

    public function priority()
    {
        return $this->hasOne('App\Priority', 'id' , 'priority_id');
    }

    public function status()
    {
        return $this->hasOne('App\TaskStatus', 'id','status_id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\TaskTag', 'task_tag_task', 'task_id', 'tag_id')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany('App\TaskCategory', 'task_category_task', 'task_id', 'category_id')->withTimestamps();
    }

    public function attachments()
    {
        return $this->hasMany('App\TaskAttachment', 'task_id');
    }

    public function comments()
    {
        return $this->hasMany('App\TaskComment', 'task_id')
                    ->orderBy('updated_at', 'desc')
                    ->take(5);
    }

    public function followers()
    {
        return $this->hasMany('App\TaskFollower', 'task_id');
    }
    public function assignee()
    {
        return $this->belongsTo('App\User', 'assignee_id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function assigneeStatuses(){
        return $this->belongsTo('App\TaskAssigneeStatus', 'assignee_status');
    }

    public function statusNote(){
        return $this->belongsToMany('App\TaskStatus', 'task_status_notes', 'task_id', 'status_id')
                    ->withPivot('note')
                    ->withTimestamps();
    }

    public function tnaItem()
    {
        return $this->hasOne('Platform\TNA\Models\TNAItem', 'id', 'tna_item_id');
    }

    public function googleCalendar()
    {
        return $this->hasOne('App\GoogleCalendar', 'id', 'google_calendar_id');
    }

    public function transformSchema(){

        $sortable = ['Title' => 'title' , 
                'DueDate' => 'dueDate' ,
                'Priority' => 'priority_id',
                'Status'   => 'status_id', 
                'Created At' => 'created_at' ,
                'Updated At' => 'updated_at'
        ];

        $filterable = ['Title' => 'tasks.title' , 
                'Priority' => 'priority_id' , 
                'Status' => 'status_id',
                'Author' => 'creator_id',
                'Assignee' => 'assignee_id'
        ];

        $filterOperation = ['Title' => 'ILIKE' , 
                'Priority' => '=' , 
                'Status' => '=',
                'Author' => '=',
                'Assignee' => '='
        ];
        
        return ['sortable' => $sortable , 'filterable' => $filterable , 'operation' => $filterOperation ];
    }

    public function reportSchema(){

        $feColumns = [ 
                        ['label' => 'Title' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Priority' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Author' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Assignee' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Category' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Tags' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Status' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'DueDate' , 'isSort' => true , 'isFilter' => false],
                        ['label' => 'Created At' , 'isSort' => true , 'isFilter' => false]
                     ];
        
        //return ['headers' => $feColumns , 'sortable' => ['orderby' => $sortable] , 'filterable' => ['type' => $filterable] , 'paginate' => $paginate ];
        return ['headers' => $feColumns ];
    }

    public static function table() {
        $model = new static;
        return $model->getTable();
    }

    public static function boot() {
        $events = new \Illuminate\Events\Dispatcher;
        static::observe(new \Platform\Tasks\Observers\TaskObserver());
        static::observe(new \Platform\Observers\Tasks\TaskObserver);
        parent::boot($events);
    }

    /**
     * Get all unseen tasks
     * @return Task
     */
    public function unseenTask()
    {
        return $this->where('assignee_id', '=' , \Auth::user()->id)
                    ->whereIn('status_id', [
                        TaskHelper::getStatusId('assigned'), 
                        TaskHelper::getStatusId('started')
                    ])
                    ->where('seen', '=', NULL)
                    ->get();
    }

    /**
     * Get all submitted Task
     * @return Task
     */
    public function getTaskByType()
    {
        return $this->where('is_submitted', '=', true)
                ->where('creator_id', '=', \Auth::user()->id)
                ->where('status_id', '=', TaskHelper::getStatusId('submitted'))
                ->get();
    }
}
