<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryItemAttribute extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'library_item_attributes';
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
        'id', 'library_item_id', 'name', 'slug', 'description', 'email',
        'admin_created', 'meta',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public static $rules = [
        'id' => 'sometimes|unique:library_item_attributes,id',
        'library_item_id' => 'required|exists:library_items,id',
        'name' => 'required|alnum_space|max:100|unique:library_item_attributes,name',
        'description' => 'required|alnum_space',
        'meta' => 'sometimes',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function libraryItem()
    {
        return $this->belongsTo('App\LibraryItem');
    }

    public function libraryItemAttributeValue()
    {
        return $this->hasMany('App\LibraryItemAttributeValue');
    }
}
