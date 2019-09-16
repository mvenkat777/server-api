<?php

namespace app;

use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
	use ActivityRecorder;

    protected $table = 'user_details';

    public $appName = 'user';

    protected $fillable = [
        'user_id','first_name','last_name','country','state','city',
        'mobile_number', 'location'
    ];

    public function getParentMeta()
    {
        return [
            'id' => $this->user->id,
            'name'=>$this->user->display_name
        ];
    }

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->first_name
        ];
    }

    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }
}
