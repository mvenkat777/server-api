<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class MaterialLibrary extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'material_library';

    protected $fillable = [
        'id', 'material_id', 'vendor_id', 'fabric_reference', 'vendor_reference',
        'fabric_style',
        'cost_local','cost_usd','cost_uom','stock','avail_greige','notes','major_customer',
        'primary_print_vendor','primary_print_vendor_cost_uom','primary_print_vendor_cost_local','primary_print_vendor_cost_usd','secondary_print_vendor','secondary_print_vendor_cost_uom','secondary_print_vendor_cost_local','secondary_print_vendor_cost_usd','fabric_lead_time','minimum_order_quantity','minimum_order_quantity_uom','minimum_order_quantity_surcharge','minimum_order_quantity_surcharge_usd','minimum_color_quantity','minimum_color_quantity_uom','minimum_color_quantity_surcharge','minimum_color_quantity_surcharge_usd','library_attachment'
    ];

    public function material(){
    	return $this->hasOne('App\Material', 'id','material_id')
                    ->whereNull('deleted_at');
    }

    public function vendor(){
        return $this->hasOne('App\Vendor', 'id', 'vendor_id')->whereNull('deleted_at');
    }

    public function primaryPrintVendor()
    {
        return $this->hasOne('App\Vendor', 'id', 'primary_print_vendor')->whereNull('deleted_at');
    }

    public function secondaryPrintVendor()
    {
        return $this->hasOne('App\Vendor', 'id', 'secondary_print_vendor')->whereNull('deleted_at');
    }

    public function customers(){
        return$this->belongsToMany(
            'App\Customer',
            'material_library_customers',
            'library_id',
            'customer_id'
        );
    }


    public $foreign = [
        'materialReferenceNo' => [
            'modelField' => 'material_id',
            'relation' => 'App\Material',
            'operation' => 'ILIKE',
            'foreignField' => 'material_reference_no'
        ],
        'construction' => [
            'modelField' => 'material_id',
            'relation' => 'App\Material',
            'operation' => 'ILIKE',
            'foreignField' => 'construction'
        ],
        'weight' => [
            'modelField' => 'material_id',
            'relation' => 'App\Material',
            'operation' => 'between',
            'foreignField' => 'weight'
        ],
        'vendorReference' => [
            'modelField' => 'vendor_id',
            'relation' => 'App\Vendor',
            'operation' => 'ILIKE',
            'foreignField' => 'code'
        ],
        'vendorCountryCode' => [
            'modelField' => 'vendor_id',
            'relation' => 'App\Vendor',
            'operation' => 'ILIKE',
            'foreignField' => 'country_code'
        ]
    ];

    public $searchable = [
        'fabricReference' => [
            'column' => 'fabric_reference',
            'operation' => 'ILIKE'
        ],
        'fabricStyle' => [
            'column' => 'fabric_style',
            'operation' => 'ILIKE'
        ],
        'costLocal' => [
            'column' => 'cost_local',
            'operation' => '='
        ],
        'costUsd' => [
            'column' => 'cost_usd',
            'operation' => '='
        ],
        'costUom' => [
            'column' => 'cost_uom',
            'operation' => 'ILIKE'
        ],
        'stock' => [
            'column' => 'stock',
            'operation' => 'ILIKE'
        ],
        'availGreige' => [
            'column' => 'avail_greige',
            'operation' => 'ILIKE'
        ],
        'notes' => [
            'column' => 'notes',
            'operation' => 'ILIKE'
        ],
        'fabricLeadTime' => [
            'column' => 'fabric_lead_time',
            'operation' => '='
        ],
        'minimumOrderQuantity' => [
            'column' => 'minimum_order_quantity',
            'operation' => '='
        ],
        'minimumOrderQuantitySurcharge' => [
            'column' => 'minimum_order_quantity_surcharge',
            'operation' => '='
        ],
        'minimumColorQuantity' => [
            'column' => 'minimum_color_quantity',
            'operation' => '='
        ],
        'minimumColorQuantitySurcharge' => [
            'column' => 'minimum_color_quantity_surcharge',
            'operation' => '='
        ]
    ];

    public $sortable = [
        'fabricReference' => 'fabric_reference',
        'fabricStyle' => 'fabric_style',
        'costLocal' => 'cost_local',
        'costUsd' => 'cost_usd',
        'costUom' => 'cost_uom',
        'stock' => 'stock',
        'availGreige' => 'avail_greige',
        'notes' => 'notes',
        'fabricLeadTime' => 'fabric_lead_time',
        'minimumOrderQuantity' => 'minimum_order_quantity',
        'minimumOrderQuantitySurcharge' => 'minimum_order_quantity_surcharge',
        'minimumColorQuantity' => 'minimum_color_quantity',
        'minimumColorQuantitySurcharge' => 'minimum_color_quantity_surcharge',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public $pivots = [
        'customer' => [
            'pivotTable' => 'App\MaterialLibraryCustomers',
            'pivotSearchField' => 'customer_id',
            'pivotResultField' => 'library_id',
            'relation' => 'App\Customer',
            'operation' => 'ILIKE',
            'relationField' => 'name',
            'modelField' => 'id',
        ]
    ];
    
}
