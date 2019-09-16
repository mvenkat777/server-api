<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use ActivityRecorder;
	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'orders';
    public $appName = 'order';

    protected $fillable = [
        'id', 'code', 'label',
        'customer_id', 'value',
        'quantity', 'size',
        'expected_delivery_date'
    ];

    public $searchable = [
        'code' => [
            'column' => 'code',
            'operation' => 'ILIKE'
        ],
        'label' => [
            'column' => 'label',
            'operation' => 'ILIKE'
        ],
        'quantity' => [
            'column' => 'quantity',
            'operation' => '='
        ],
        'value' => [
            'column' => 'value',
            'operation' => '='
        ],
        'size' => [
            'column' => 'size',
            'operation' => 'ILIKE'
        ],
        'expectedDeliveryDate' => [
            'column' => 'expected_delivery_date',
            'operation' => 'date'
        ],
        'createdAt' => [
            'column' => 'created_at',
            'operation' => 'date'
        ],
        'updatedAt' => [
            'column' => 'updated_at',
            'operation' => 'date'
        ]
    ];

    public $sortable = [
        'code' => 'code',
        'label' => 'label',
        'quantity' => 'quantity',
        'value' => 'value',
        'size' => 'size',
        'expectedDeliveryDate' => 'expected_delivery_date',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    public $globalSearchColumns = ['code', 'label'];

    public $incrementing = false;

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name' => $this->code
        ];
    }

    public function vendors()
    {
        return $this->belongsToMany('App\Vendor', 'order_vendors', 'order_id', 'vendor_id');
    }

    public function techpacks()
    {
        return $this->belongsToMany('App\Techpack', 'order_techpacks', 'order_id', 'techpack_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function transformSchema(){

        $sortable = ['Order Code' => 'code' ,
                'Order Label' => 'label' ,
                'Order Size' => 'size' ,
                'Order Quantity' => 'quantity' ,
                'Expected Delivery Date'   => 'expected_delivery_date',
                'Order Value' => 'value',
                'Created At' => 'created_at' ,
                'Updated At' => 'updated_at'
        ];

        $filterable = ['Order Code' => 'code' ,
                'Order Label' => 'label' ,
                'Order Size' => 'size' ,
                'Order Quantity' => 'quantity' ,
                'Expected Delivery Date'   => 'expected_delivery_date',
                'Order Value' => 'value'
        ];

        $filterOperation = ['Order Code' => 'ILIKE' ,
                'Order Label' => 'ILIKE' ,
                'Order Size' => 'ILIKE' ,
                'Order Quantity' => '=' ,
                'Expected Delivery Date'   => '<=',
                'Order Value' => '='
        ];

        return ['sortable' => $sortable , 'filterable' => $filterable , 'operation' => $filterOperation ];
    }

    public function reportSchema(){

        $feColumns = [
                        ['label' => 'Order Code' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Order Label' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Customer Name' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Order Size' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Order Quantity' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Expected Delivery Date' , 'isSort' => true , 'isFilter' => false],
                        ['label' => 'Order Value' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Vendor' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Created At' , 'isSort' => true , 'isFilter' => false]
                     ];

        return ['headers' => $feColumns ];
    }
}
