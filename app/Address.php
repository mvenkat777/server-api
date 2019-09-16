<?php

namespace app;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
	use SoftDeletes;

    protected $table = 'address';

    protected $fillable = [ 'label', 'line1','line2', 'city', 'state','zip', 'country', 'phone',
    						'air_cargo_port', 'sea_cargo_port', 'is_primary'];
}
