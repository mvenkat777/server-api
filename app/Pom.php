<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Pom extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'archived_at'];
    protected $fillable = ['id', 'name', 'category_code', 'product_type_code', 
    	'size_range_name', 'size_range_value', 'size_type_id', 'base_size', 'archived_at'
    ];

    protected $table = 'poms';

    /**
     * Get All pom sheet rows
     * 		
     * @return App\PomSheet
     */
    public function pomSheet()
    {
    	return $this->hasMany('App\PomSheet', 'pom_id', 'id')
            ->whereNull('archived_at')->orderBy('updated_at');
    }

    public function archivedPomSheet()
    {
        return $this->hasMany('App\PomSheet', 'pom_id', 'id')
            ->whereNotNull('archived_at')->orderBy('updated_at');
    }

    /**
     *Get App\Category
     * 
     * @return  mixed
     */
    public function category()
    {
    	return $this->hasOne('\App\ProductCategory', 'code', 'category_code');
    }

    /**
     *Get App\ProductType
     * 
     * @return  mixed
     */
    public function productType()
    {
    	return $this->hasOne('\App\ProductType', 'code', 'product_type_code');
    }

    /**
     * Get App\SizeType 
     * 
     * @return  mixed
     */
    public function sizeType()
    {
    	return $this->hasOne('\App\SizeType', 'id', 'size_type_id');
    }

    // /**
    //  * Get App\SizeRange
    //  * 
    //  * @return  mixed
    //  */
    // public function sizeRange()
    // {
    // 	return $this->hasOne('\App\SizeRange', 'code', 'size_range_code');
    // }
}
