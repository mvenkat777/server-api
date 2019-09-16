<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorPaymentTerm extends Model
{
    protected $table = 'vendor_payment_terms';
    protected $fillable=['name'];
}