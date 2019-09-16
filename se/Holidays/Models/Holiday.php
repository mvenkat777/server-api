<?php

namespace Platform\Holidays\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use SoftDeletes;

    protected $table = 'holidays';

    public $incrementing = false;

    protected $fillable = [
        'id', 'date', 'day', 'is_work_day', 'description', 'affected_supply_chain', 'location_id', 'deleted_at', 'year'
    ];

    public function location()
    {
        return $this->belongsTo('Platform\Holidays\Models\Location', 'location_id');
    }

}
