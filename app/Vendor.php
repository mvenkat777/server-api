<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use ActivityRecorder;
	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at','archived_at'];
    protected $table = 'vendors';
    public $appName = 'vendor';
    protected $fillable = ['id', 'code', 'name', 'business_entity', 'country_code','import_export_license','tax_id', 'vat_sales_tax_reg', 'company_reg',
                            'annual_shipped_turnover',  'annual_shipped_quantity'];


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
            'country_code' => [
                'column' => 'business_entity',
                'operation' => '='
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

    public $pivots = [
        'service' => [
            'pivotTable' => 'App\VendorVendorService',
            'pivotSearchField' => 'vendor_service_id',
            'pivotResultField' => 'vendor_id',
            'relation' => 'App\VendorService',
            'operation' => 'ILIKE',
            'relationField' => 'name',
            'modelField' => 'id',
        ]
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
        'business_entity','country_code',
        'created_at', 'updated_at' ,'archived_at'
    ];

    public $globalSearchColumns = ['code', 'name'];

    public $incrementing = false;

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->name
        ];
    }

    public function country()
    {
        return $this->hasOne('\App\Country', 'code', 'country_code');
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Contact', 'vendor_contact', 'vendor_id', 'contact_id');
    }

    public function addresses()
    {
        return $this->belongsToMany('App\Address', 'vendor_address', 'vendor_id', 'address_id');
    }

    public function banks()
    {
        return $this->belongsToMany('App\BankDetail', 'vendor_bank_details', 'vendor_id', 'bank_id');
    }

    public function partners()
    {
        return $this->hasMany('App\VendorPartner', 'vendor_id');
    }

    public function types()
    {
        return $this->belongsToMany('App\VendorType', 'vendor_vendor_types', 'vendor_id', 'vendor_type_id');
    }

    public function services()
    {
        return $this->belongsToMany('App\VendorService', 'vendor_vendor_service', 'vendor_id', 'vendor_service_id');
    }

    public function paymentTerms()
    {
        return $this->belongsToMany('App\VendorPaymentTerm', 'vendor_vendor_payment_terms', 'vendor_id', 'vendor_payment_terms_id');
    }

    public function capabilities()
    {
        return $this->belongsToMany('App\VendorCapability', 'vendor_vendor_capability', 'vendor_id', 'vendor_capability_id')->withPivot(['inhouse', 'outsource', 'moq', 'capacity'])
            ->orderBy('vendor_capability_id');
    }


    public function transformSchema(){

        $sortable = ['Vendor Name' => 'name' ,
                'Vendor Code' => 'code' ,
                'Business Entity'   => 'business_entity',
                'Created At' => 'created_at' ,
                'Updated At' => 'updated_at'
        ];

        $filterable = ['Vendor Name' => 'name' ,
                'Vendor Code' => 'code' ,
                'Business Entity'   => 'business_entity',
        ];

        $filterOperation = ['Vendor Name' => 'ILIKE' ,
                'Vendor Code' => 'ILIKE' ,
                'Business Entity' => 'ILIKE'
        ];

        return ['sortable' => $sortable , 'filterable' => $filterable , 'operation' => $filterOperation ];
    }

    public function reportSchema(){

        $feColumns = [
                        ['label' => 'Vendor Name' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Vendor Code' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Business Entity' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Type of Vendor' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Type of Service' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Payment Terms' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Created At' , 'isSort' => true , 'isFilter' => false]
                     ];

        return ['headers' => $feColumns ];
    }
}
