<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use ActivityRecorder;

	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at','archived_at'];
    protected $table = 'customers';

    public $appName = 'customer';
    protected $fillable = [
        'id', 'code', 'name', 'business_entity', '
        import_export_license',
    	'tax_id', 'vat_sales_tax_reg',
        'company_reg'
    ];

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->name
        ];
    }

    public $searchable = [
            'code' => [
                'column' => 'code',
                'operation' => 'ILIKE'
            ],
            'name' => [
                'column' => 'name',
                'operation' => 'ILIKE'
            ],
            'businessEntity' => [
                'column' => 'business_entity',
                'operation' => 'ILIKE'
            ],
            'createdAt' => [
                'column' => 'created_at',
                'operation' => 'date'
            ],
            'updatedAt' => [
                'column' => 'updated_at',
                'operation' => 'date'
            ],
        ];

    public $sortable = [
        'code' => 'code',
        'name' => 'name',
        'businessEntity' => 'business_entity',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public $metaFields = [
        'id', 'code', 'name',
        'business_entity',
        'created_at', 'updated_at' ,'archived_at'
    ];

    public $globalSearchColumns = ['code', 'name'];

    public $incrementing = false;

    public function contacts()
    {
    	return $this->belongsToMany('App\Contact', 'customer_contact', 'customer_id', 'contact_id');
    }

    public function addresses()
    {
    	return $this->belongsToMany('App\Address', 'customer_address', 'customer_id', 'address_id');
    }

    public function brands()
    {
    	return $this->hasMany('App\CustomerBrand', 'customer_id', 'id');
    }

    public function partners()
    {
        return $this->hasMany('App\CustomerPartner', 'customer_id', 'id');
    }

    public function types()
    {
        return $this->belongsToMany('App\CustomerType', 'customer_customer_types', 'customer_id', 'customer_type_id');
    }

    public function services()
    {
        return $this->belongsToMany('App\CustomerService', 'customer_customer_service', 'customer_id', 'customer_service_id');
    }

    public function paymentTerms()
    {
        return $this->belongsToMany('App\CustomerPaymentTerm', 'customer_customer_payment_terms',
                         'customer_id', 'customer_payment_terms_id');
    }

    public function requirements()
    {
        return $this->belongsToMany('App\CustomerRequirement', 'customer_customer_requirements',
                     'customer_id', 'customer_requirement_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Order', 'customer_id');
    }

    /**
     * A customers belongs to many users
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'customer_user', 'customer_id', 'user_id');
    }

    /**
     * A customer has many collabs
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function collab()
    {
        return $this->hasOne('Platform\Customer\Models\Collab', 'customer_id');
    }

    public function lines()
    {
        return $this->hasMany('App\Line', 'customer_id');
    }

    public function transformSchema(){

        $sortable = ['Customer Name' => 'name' ,
                'Customer Code' => 'code' ,
                'Business Entity'   => 'business_entity',
                'Created At' => 'created_at' ,
                'Updated At' => 'updated_at'
        ];

        $filterable = ['Customer Name' => 'name' ,
                'Customer Code' => 'code' ,
                'Business Entity'   => 'business_entity',
        ];

        $filterOperation = ['Customer Name' => 'ILIKE' ,
                'Customer Code' => 'ILIKE' ,
                'Business Entity' => 'ILIKE'
        ];

        return ['sortable' => $sortable , 'filterable' => $filterable , 'operation' => $filterOperation ];
    }

    public function reportSchema(){

        $feColumns = [
                        ['label' => 'Customer Name' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Customer Code' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Business Entity' , 'isSort' => true , 'isFilter' => true],
                        //['label' => 'Brand Name' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Type of Customer' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Type of Service' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Customer Requirement' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Payment Terms' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Created At' , 'isSort' => true , 'isFilter' => false]
                     ];

        return ['headers' => $feColumns ];
    }
}
