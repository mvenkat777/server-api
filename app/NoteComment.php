<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NoteComment extends Model
{
	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'note_comments';
    protected $fillable=['note_id', 'comment', 'commented_by'];

	public function user()
	{
		return $this->belongsTo('App\User', 'commented_by');
	}
    
}