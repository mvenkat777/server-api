<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\Model;

class CustomerPartner extends Model
{
    use ActivityRecorder;
	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'customer_partners';
    protected $fillable = ['id', 'customer_id','name','role'];

    protected $appName = 'customer';
    protected $modelVerb = 'add';

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->name
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

    public function contact()
    {
    	return $this->belongsToMany('App\Contact', 'customer_partners_contact', 'customer_partner_id', 
                                    'contact_id');
    }

    public function address()
    {
        return $this->belongsToMany('App\Address', 'customer_partner_address', 'customer_partner_id', 
                                    'address_id');
    }
}