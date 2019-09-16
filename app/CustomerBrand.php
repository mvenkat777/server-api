<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Platform\App\Activity\ActivityRecorder;

class CustomerBrand extends Model
{
    use ActivityRecorder;

    protected $table = 'customer_brands';
    protected $fillable=['id', 'customer_id', 'brand'];

    protected $appName = 'customer';
    protected $modelApp = 'add';

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->brand
        ];
    }

    public function getParentMeta()
    {
        return [
            'id' => $this->customer->id,
            'name'=>$this->customer->name
        ];
    }
    
    public function customer()
    {
    	return $this->belongsTo('App\Customer', 'customer_id');
    }
}