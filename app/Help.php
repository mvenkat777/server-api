<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Help extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'help';

    protected $fillable = [
         'id', 'title','slug', 'description', 'app_id', 'feedback', 'author_log', 'like' ,'dislike',
         'owner' 
    ];
     
    public function appList()
    {
        return $this->hasOne('App\AppsList', 'id', 'app_id');
    }

    public function usersLike()
    {
        return $this->belongsToMany('App/User', 'help_like', 'help_id', 'user_id')->withPivot('is_like');
    }
}
