<?php

namespace Platform\Boards\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFolderComment extends Model
{
   /**
    * The table name
    */
	protected $table = 'product_folder_comments';

    /**
     * Fillable fields
     */
	protected $fillable = ['id', 'product_folder_id', 'comment', 'commentator_id'];

    /**
     * There are no comments without some one commenting.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function commentator()
    {
        return $this->hasOne('App\User', 'id', 'commentator_id');
    }
}

