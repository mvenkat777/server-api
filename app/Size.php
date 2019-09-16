<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
	use SoftDeletes;
	
	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
		
    protected $fillable = ['size', 'size_type_id', 'code'];

	protected $table = 'sizes';

	/**
	 * Has One relationship
	 * @return  \App\sizeType
	 */
	public function sizeType()
    {
    	return $this->hasOne('App\SizeType', 'id', 'size_type_id');
    }    
}
