<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskCategory extends Model
{
    use SoftDeletes;

    protected $table = 'task_categories';

    protected $fillable = ['id', 'title'];

    public function tasks()
    {
        return $this->belongsToMany(
            'App\Task',
            'task_category_task',
            'category_id',
            'task_id'
        )->withTimestamps();
    }

    /**
     * Get all category
     * @return TaskCategory
     */
    public function getAllCategory()
    {
        return $this->orderBy('title', 'asc')->get();
    }
}
