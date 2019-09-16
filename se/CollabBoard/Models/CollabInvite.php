<?php

namespace Platform\CollabBoard\Models;

use Illuminate\Database\Eloquent\Model;

class CollabInvite extends Model
{
	//use SoftDeletes;

   /**
    * The table name
    */
	protected $table = 'collab_invites';

    /**
     * Fillable fields
     */
	protected $fillable = ['id', 'collab_id', 'user_id', 'invite_code', 'permission', 'is_active'];

    /**
     * An invite has one user
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}

