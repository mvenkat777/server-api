<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
// use Platform\Observers\UserTokenObserver;

class UserToken extends Model
{
    protected $table = 'user_tokens';

    protected $fillable = ['user_id', 'token', 'expires_at'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // public static function table() {
    //     $model = new static;
    //     return $model->getTable();
    // }
 
    // public static function boot() {
    //     $events = new \Illuminate\Events\Dispatcher;
    //     static::observe(new UserTokenObserver());
    //     parent::boot($events);
    // }
}
