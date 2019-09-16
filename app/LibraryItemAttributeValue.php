<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LibraryItemAttributeValue.
 */
class LibraryItemAttributeValue extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'library_item_attribute_values';
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
        'id', 'library_item_id', 'library_item_attribute_id', 'value',
        'admin_created', 'meta',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public static $rules = [
        'id' => 'sometimes|unique:library_item_attribute_values,id',
        'library_item_id' => 'required|exists:library_items,id',
        'library_item_attribute_id' => 'required|exists:library_item_attributes,id',
        'value' => 'required|alnum_space|max:100|unique:library_item_attribute_values,value',
        'meta' => 'sometimes',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function libraryItemAttribute()
    {
        return $this->belongsTo('App\LibraryItemAttribute');
    }
}
