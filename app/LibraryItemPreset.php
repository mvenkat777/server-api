<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LibraryItemPreset.
 */
class LibraryItemPreset extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'library_item_presets';
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * The presets that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'library_item_id', 'name', 'description', 'data',
        'admin_created', 'meta',
    ];

    /**
     * The presets excluded from the model's JSON form.
     *
     * @var array
     */
    public static $rules = [
        'id' => 'sometimes|unique:library_item_presets,id',
        'libraryItemId' => 'required|exists:library_items,id',
        'name' => 'required|alnum_space|max:100|unique:library_item_presets,name',
        'data' => 'required',
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
}
