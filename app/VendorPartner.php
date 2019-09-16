<?php

namespace App;

use App\Contact;
use Illuminate\Database\Eloquent\Model;
use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorPartner extends Model
{
    use ActivityRecorder;
	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'vendor_partners';
    protected $fillable=['id','vendor_id','name','role'];

    protected $appName = 'vendor';
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
            'id' => $this->vendor->id,
            'name'=>$this->vendor->name
        ];
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id');
    }

    public function contact()
    {
        return $this->belongsToMany('App\Contact', 'vendor_partners_contact', 'vendor_partner_id', 'contact_id');
    }

    public function address()
    {
        return $this->belongsToMany('App\Address', 'vendor_partner_address', 'vendor_partner_id', 'address_id');
    }
}