<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class AppsList extends Model
{
    protected $table = 'apps_list';

    protected $fillable = ['app_name', 'icon', 'status'];

    public function help()
    {
    	return $this->hasMany('App\Help', 'app_id');
    }

    public function allApps()
    {
    	return 1;
    }

}
