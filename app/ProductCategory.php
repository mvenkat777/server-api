<?php

namespace App;

use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use ActivityRecorder;
    use SoftDeletes;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    protected $fillable = ['code', 'category', 'description', 'classification_code'];
    protected $primaryKey = 'code';
    public $incrementing = false;
    public $timestamps = false;
    public $appName = 'pom';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function products()
    // {
    //     return $this->belongsToMany(
    //         'App\ProductList',
    //         'product_category_product_list',
    //         'product_category_code',
    //         'product_list_code'
    //     );
    // }
    
    public function classification()
    {
        return hasOne('App\Classification', 'classification_code');
    }
}
