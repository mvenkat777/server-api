<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNote extends Model
{
	use ActivityRecorder;

	public $appName = 'user';
	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'user_notes';
    protected $fillable=['written_by','user_id','status','note'];

    protected $document = ['note'];
    protected $modelVerb = 'add';


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
            'name'=>$this->note
        ];
    }

    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }
}