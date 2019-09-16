<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskTag extends Model
{
    use SoftDeletes;

    protected $table = 'task_tags';

    protected $fillable = ['id','title'];

    public $incrementing = false;

    public function tasks()
    {
        return $this->belongsToMany(
            'App\Task',
            'task_tag_task',
            'tag_id',
            'task_id'
        )->withTimestamps();
    }

    /**
     * @return TaskTags
     */
    public function getAllTags()
    {
        return $this->orderBy('title', 'asc')->get();
    }
}
