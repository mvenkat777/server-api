<?php

namespace App;

use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductList extends Model
{
    use ActivityRecorder;
    use SoftDeletes;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = ['code', 'product', 'description', 'product_type_code'];
    protected $primaryKey = 'code';
    public $incrementing = false;
    public $timestamps = false;

    public $appName = 'pom';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productType()
    {
        return $this->hasOne('App\ProductType', 'code', 'product_type_code');
    }
}
