<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'contacts';
    protected $fillable=['label','mobile_number1','mobile_number2','mobile_number3','email1','email2',
    					'skype_id','designation', 'is_primary'];
}