<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryItem extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'library_items';
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'slug', 'description', 'email', 'admin_created', 'meta',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public static $rules = [
        'id' => 'sometimes|unique:library_items,id',
        'name' => 'required|alnum_space|max:100|unique:library_items,name',
        'description' => 'required|alnum_space',
        'email' => 'sometimes|email',
        'meta' => 'sometimes',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
//    public function user()
//    {
//        return $this->belongsTo( 'App\User' );
//    }


    public function libraryItemAttribute()
    {
        return $this->hasMany('App\LibraryItemAttribute', 'library_item_id');
    }
}
