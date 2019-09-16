<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class VendorVendorService extends Model
{
    protected $table = 'vendor_vendor_service';
    protected $fillable = ['vendor_id','vendor_service_id'];
}
