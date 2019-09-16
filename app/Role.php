<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = ['name','description','group_id','apps_permissions'];

    protected $casts = [
        'apps_permissions' => 'json',
    ];

    public function group()
    {
        return $this->hasOne('App\Group', 'group_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'role_user', 'role_id', 'user_id');
    }

}
