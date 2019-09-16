<?php

namespace Platform\Boards\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFolder extends Model
{
	use SoftDeletes;

   /**
    * The table name
    */
	protected $table = 'product_folders';

    /**
     * Fillable fields
     */
	protected $fillable = ['id', 'name', 'cover', 'archived_at'];

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * A board has many picks
     *
     */
    public function picks()
    {
        return $this->belongsToMany('Platform\Picks\Models\Pick', 'product_folder_pick', 'product_folder_id', 'pick_id');
    }

    /**
     * A board has many boards
     *
     */
    public function boards()
    {
        return $this->belongsToMany('Platform\Boards\Models\Board', 'board_product_folder', 'product_folder_id', 'board_id');
    }

    /**
     * A product folder has many comments
     *
     */
    public function comments()
    {
        return $this->hasMany('Platform\Boards\Models\ProductFolderComment', 'product_folder_id', 'id');
    }
}

