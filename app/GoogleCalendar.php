<?php

namespace App;

use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoogleCalendar extends BaseModel
{
    use SoftDeletes;

    protected $table = 'google_calendar';

    public $appName = 'task';
    
    public $incrementing = false;

    public $fillable = ['id', 'calendar_id', 'event_id', 'created_at', 'updated_at'];

    public function task()
    {
        return \App\Task::where('google_calendar_id', $this->id)->first();
    }
}
