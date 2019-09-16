<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskRejectLog extends Model
{
    use SoftDeletes;

    protected $table = 'tasks_rejection_log';

    public $incrementing = false;

    protected $fillable = [
        'id','task_id','creator_id','assignee_id','reason'
    ];

    public function tasks()
    {
        return $this->belongsTo('App\Task', 'task_id');
    }

}
