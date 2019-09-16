<?php

namespace Platform\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collab extends Model
{
	//use SoftDeletes;

   /**
    * The table name
    */
	protected $table = 'collabs';

    /**
     * Fillable fields
     */
	protected $fillable = ['id', 'name', 'customer_id', 'url', 'logo', 'sales_lead_id'];

	/**
	 * A collab belongs to one customer
	 */
	public function customer()
	{
		return $this->hasOne('App\Customer', 'id', 'customer_id');
	}

	/**
	 * A collab has many users
	 */
	public function users()
	{
        return $this->belongsToMany(
            'App\User', 
            'collab_users', 
            'collab_id', 
            'user_id'
        )->withPivot(['role', 'is_active']);;
	}

	/**
	 * A collab has many boards
	 */
	public function boards()
	{
        return $this->belongsToMany(
            'Platform\Boards\Models\Board', 
            'collab_board', 
            'collab_id', 
            'board_id'
        );
	}

	/**
	 * A collab has one Sales Lead
	 */
	public function salesLead()
	{
        return $this->hasOne(
            'App\User', 
            'id',
            'sales_lead_id' 
        );
	}
}

