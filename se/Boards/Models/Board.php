<?php

namespace Platform\Boards\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
	use SoftDeletes;

   /**
    * The table name
    */
	protected $table = 'boards';

    /**
     * Fillable fields
     */
	protected $fillable = ['id', 'name', 'cover', 'description', 'sales_lead_id', 'author_id'];

    public $timestamps = ['deleted_at', 'archived_at'];

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * A board has one author
     *
     */
    public function author()
    {
        return $this->hasOne('App\User', 'id', 'author_id');
    }

    /**
     * A boar can have one sales lead
     *
     */
    public function salesLead()
    {
        return $this->hasOne('App\User', 'id', 'sales_lead_id');
    }

    /**
     * A board has many picks
     *
     */
    public function picks()
    {
        return $this->belongsToMany('Platform\Picks\Models\Pick', 'board_pick', 'board_id', 'pick_id');
    }

    /**
     * A board has many product folders
     *
     */
    public function productFolders()
    {
        return $this->belongsToMany('Platform\Boards\Models\ProductFolder', 'board_product_folder', 'board_id', 'product_folder_id');
    }

    /**
     * A board could belong to many collabs
     */
    public function collabs()
    {
        return $this->belongsToMany(
            'Platform\Customer\Models\Collab',
            'collab_board',
            'board_id',
            'collab_id'
        );
    }
}

