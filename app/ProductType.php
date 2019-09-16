<?php

namespace App;

use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
	use ActivityRecorder;
    protected $fillable = ['code', 'product_type'];
    protected $primaryKey = 'code';
    public $incrementing = false;
    public $timestamps = false;

    public $appName = 'pom';
}