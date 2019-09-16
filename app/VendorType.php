<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorType extends Model
{
    protected $table = 'vendor_types';
    protected $fillable=['name'];
}