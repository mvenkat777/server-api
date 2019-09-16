<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Platform\App\Activity\ActivityRecorder;

class TechpackComment extends Model
{
    // use ActivityRecorder;

    protected $table = 'techpack_comments';
    protected $fillable = [
        'id', 'techpack_id', 'user_id', 'file', 'comment', 'parent_id',
    ];
    protected $appName = 'techpack';
    protected $modelVerb = 'comment';
    
    protected $childFields = ['user_id' => 'users|user'];

    protected $relation = [
        'user_id' => 'user|Platform\Users\Transformers\MetaUserTransformer',
    ];


    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public static function table() {
        $model = new static;
        return $model->getTable();
    }

    public static function boot() {
        $events = new \Illuminate\Events\Dispatcher;
        static::observe(new \Platform\Observers\Techpack\TechpackCommentObserver);
        parent::boot($events);
    }
}
