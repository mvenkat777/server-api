<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SizeRange extends Model
{
	use SoftDeletes;
	
	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = ['code', 'range', 'range_value', 'size_type_id', 'archived_at'];
    protected $table = 'size_ranges';

    public function sizeType()
    {
    	return $this->hasOne('\App\SizeType', 'id', 'size_type_id');
    }
}
