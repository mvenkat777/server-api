<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class MaterialLibraryCustomers extends Model
{
    protected $table = 'material_library_customers';
    protected $fillable = ['library_id','customer_id'];
}
