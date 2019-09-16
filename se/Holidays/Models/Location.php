<?php

namespace Platform\Holidays\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

    protected $table = 'locations';

    public $incrementing = false;

    protected $fillable = [
        'id', 'city', 'state', 'country', 'postal_code', 'address'
    ];

    public function holidays()
    {
        return $this->hasMany('Platform\Holidays\Models\Holiday', 'location_id');
    }

}
