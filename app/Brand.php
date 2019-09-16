<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
	protected $table = 'customer_brands';
    protected $fillable = ['id', 'brand', 'customer_id'];
}