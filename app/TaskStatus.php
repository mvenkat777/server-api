<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Platform\App\Activity\ActivityRecorder;

class TaskStatus extends Model
{
    use ActivityRecorder;

    protected $appName = 'Task';

    protected $table = 'task_status';

    protected $fillable=['status'];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function statusNote(){
        return $this->belongsToMany('App\Task', 'taskStatusNotes', 'statusId', 'taskId')
        			->withPivot('note')
                    ->withTimestamps();
    }
}
