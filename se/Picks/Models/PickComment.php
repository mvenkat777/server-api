<?php

namespace Platform\Picks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PickComment extends Model
{
   /**
    * The table name
    */
	protected $table = 'pick_comments';

    /**
     * Fillable fields
     */
	protected $fillable = ['id', 'pick_id', 'comment', 'commentator_id'];

    public function commentator()
    {
        return $this->hasOne('App\User', 'id', 'commentator_id');
    }
}

