<?php

namespace Platform\Picks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pick extends Model
{
	use SoftDeletes;

   /**
    * The table name
    */
	protected $table = 'picks';

    /**
     * Fillable fields
     */
	protected $fillable = ['id', 'name', 'pick', 'uploader_id', 'archived_at'];

    /**
     * One pick has one uploader
     *
     */
    public function uploader()
    {
        return $this->hasOne('App\User', 'id', 'uploader_id');
    }

    /**
     * One pick belongs to many boards
     *
     */
    public function boards()
    {
        return $this->belongsToMany('Platform\Boards\Models\Board', 'board_pick', 'pick_id', 'board_id');
    }

    /**
     * A pick has many comments
     *
     */
    public function comments()
    {
        return $this->hasMany('Platform\Picks\Models\PickComment', 'pick_id', 'id');
    }

    /**
     * One pick belongs to many projectFolders
     *
     */
    public function productFolders()
    {
        return $this->belongsToMany('Platform\Boards\Models\ProductFolder', 'product_folder_pick', 'pick_id', 'product_folder_id');
    }

    /**
     * A pick can be liked by many users
     *
     */
    public function favouritedUsers()
    {
        return $this->belongsToMany(
            'App\User',
            'pick_favourites',
            'pick_id',
            'user_id'
        );
    }
}

