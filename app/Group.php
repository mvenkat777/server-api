<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';

    protected $fillable = ['name', 'description', 'owner_email'];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }
}
