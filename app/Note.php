<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'notes';
    protected $fillable=['id', 'title', 'description', 'created_by'];
    public $incrementing = false;

    public function share()
    {
        return $this->belongsToMany('App\User', 'note_shared', 'note_id', 'shared_to')
                    ->withPivot('shared_by')
                    ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany('App\NoteComment', 'note_id', 'id');
    }
    
}
