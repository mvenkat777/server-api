<?php

namespace Platform\CollabBoard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollabUser extends Model
{
	//use SoftDeletes;

   /**
    * The table name
    */
	protected $table = 'collab_users';

    /**
     * Fillable fields
     */
	protected $fillable = ['id', 'collab_id', 'user_id', 'invite_code', 'role', 'is_active'];
}

