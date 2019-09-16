<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorService extends Model
{
    protected $table = 'vendor_service';
    protected $fillable=['name'];
}