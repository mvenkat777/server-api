<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
	protected $table = 'classifications';
    protected $fillable = ['classification', 'code'];
}
