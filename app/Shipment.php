<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Platform\Observers\ShipmentObserver;

class Shipment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'shipments';

    protected $fillable = [
        'id', 'shipment_type', 'shipped_date', 'shipped_from',
        'shipped_destination', 'item_details', 'tracking_id',
        'tracking_provider', 'tracking_status', 'user_id', 'techpack_id',
        'product_id',
    ];

    public $searchable = [
        'shipmentType' => [
            'column' => 'shipment_type',
            'operation' => 'ILIKE'
        ],
        'shippedDate' => [
            'column' => 'shipped_date',
            'operation' => 'date'
        ],
        'shippedFrom' => [
            'column' => 'shipped_from',
            'operation' => 'ILIKE'
        ],
        'shippedDestination' => [
            'column' => 'shipped_destination',
            'operation' => 'ILIKE'
        ],
        'trackingId' => [
            'column' => 'tracking_id',
            'operation' => '='
        ],
        'trackingProvider' => [
            'column' => 'tracking_provider',
            'operation' => 'ILIKE'
        ],
        'trackingStatus' => [
            'column' => 'tracking_status',
            'operation' => 'ILIKE'
        ],
        'updatedAt' => [
            'column' => 'updated_at',
            'operation' => 'date'
        ],
        'createdAt' => [
            'column' => 'created_at',
            'operation' => 'date'
        ]
    ];

    public $sortable = [
        'shipmentType' => 'shipment_type',
        'shippedDate' => 'shipped_date',
        'shippedFrom' => 'shipped_from',
        'shippedDestination' => 'shipped_destination',
        'trackingId' => 'tracking_id',
        'trackingProvider' => 'tracking_provider',
        'trackingStatus' => 'tracking_status',
        'updatedAt' => 'updated_at',
        'createdAt' => 'created_at'
    ];

    public $incrementing = false;

    // public static function table() {
    //     $model = new static;
    //     return $model->getTable();
    // }
 
    // public static function boot() {
    //     $events = new \Illuminate\Events\Dispatcher;
    //     static::observe(new ShipmentObserver());
    //     parent::boot($events);
    // }

    public function transformSchema()
    {

        $sortable = ['Tracking Id' => 'tracking_id' ,
                    'Shipment Type' => 'shipment_type' ,
                    'Shipped From' => 'shipped_from' ,
                    'Shipped Destination' => 'shipped_destination' ,
                    'Shipped Date' => 'shipped_date' ,
                    'Shipped Status' => 'tracking_status' ,
                    'Created At' => 'created_at' ,
                    'Updated At' => 'updated_at'
        ];

        $filterable = ['Tracking Id' => 'tracking_id' ,
                'Shipment Type' => 'shipment_type' ,
                'Shipped From' => 'shipped_from' ,
                'Shipped Destination' => 'shipped_destination' ,
                'Shipped Status' => 'tracking_status'
        ];

        $filterOperation = ['Tracking Id' => 'ILIKE' ,
                'Shipment Type' => 'ILIKE' ,
                'Shipped From' => 'ILIKE' ,
                'Shipped Destination' => 'ILIKE' ,
                'Shipped Status' => 'ILIKE'
        ];


        return ['sortable' => $sortable , 'filterable' => $filterable , 'operation' => $filterOperation ];
    }

    public function reportSchema()
    {

        $feColumns = [
                        ['label' => 'Tracking Id' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Shipment Type' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Shipped From' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Shipped Destination' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Shipped Date' , 'isSort' => true , 'isFilter' => false],
                        ['label' => 'Shipped Status' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Created At' , 'isSort' => true , 'isFilter' => false],
                        ['label' => 'Updated At' , 'isSort' => true , 'isFilter' => false]
                     ];

        //return ['headers' => $feColumns , 'sortable' => ['orderby' => $sortable] , 'filterable' => ['type' => $filterable] , 'paginate' => $paginate ];
        return ['headers' => $feColumns ];
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
