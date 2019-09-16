<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    protected $table = 'bank_details';
    protected $fillable=['id','name_on_account','bank_name','account_number','account_type'
                        ,'branch_address', 'bank_code', 'note'];


    public function addresses()
    {
        return $this->belongsToMany('App\Address', 'bank_address', 'bank_id', 'address_id');
    }
}