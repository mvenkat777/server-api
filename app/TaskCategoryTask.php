<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class TaskCategoryTask extends Model
{
    protected $table = 'task_category_task';

    public function getDateAttribute($value)
    {
        $this->attributes['created_at'] = Carbon\Carbon::createFromFormat(
            'd-m-Y h:i',
            $value
        );
        $this->attributes['updated_at'] = Carbon\Carbon::createFromFormat(
            'd-m-Y h:i',
            $value
        );
        $this->attributes['deleted_at'] = Carbon\Carbon::createFromFormat(
            'd-m-Y h:i',
            $value
        );
    }
}
