<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\Observers\PaymentObserver;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'general_payments';

    protected $fillable = [
        'id', 'email', 'name', 'product_name', 'description', 'amount',
        'user_object', 'user_location', 'product_link', 'sender_name',
        'sender_email', 'status', 'payment_status', 'upload_link_object',
    ];

    public $searchable = [
        'email' => [
            'column' => 'email',
            'operation' => 'ILIKE'
        ],
        'name' => [
            'column' => 'name',
            'operation' => 'ILIKE'
        ],
        'productName' => [
            'column' => 'product_name',
            'operation' => 'ILIKE'
        ],
        'description' => [
            'column' => 'description',
            'operation' => 'ILIKE'
        ],
        'amount' => [
            'column' => 'amount',
            'operation' => 'between'
        ],
        'senderName' => [
            'column' => 'sender_name',
            'operation' =>'ILIKE'
        ],
        'sender_email' => [
            'column' => 'sender_email',
            'operation' => 'ILIKE'
        ],
        'status' => [
            'column' => 'status',
            'operation' => 'ILIKE'
        ],
        'paymentStatus' => [
            'column' => 'payment_status',
            'operation' => '='
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
        'email' =>  'email',
        'name' =>  'name',
        'productName' =>  'product_name',
        'description' =>  'description',
        'amount' =>  'amount',
        'creatorName' =>  'sender_name',
        'sender_email' =>  'sender_email',
        'status' =>  'status',
        'paymentStatus' =>  'payment_status', 
        'createdAt' => 'created_at',   
        'updatedAt' => 'updated_at'   
    ];


    public $incrementing = false;

    /**
     * Set the soft delete date part.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    //  public static function table() {
    //     $model = new static;
    //     return $model->getTable();
    // }

    // public static function boot() {
    //     $events = new \Illuminate\Events\Dispatcher;
    //     static::observe(new PaymentObserver());
    //     parent::boot($events);
    // }

    public function transformSchema()
    {

        $sortable = ['Full Name' => 'name' ,
                    'Email Address' => 'email' ,
                    'Product Name' => 'product_name' ,
                    'Amount' => 'amount' ,
                    'Status' => 'status' ,
                    'Creator Email' => 'sender_email',
                    'Created At' => 'created_at' 
                    ];

        $filterable = ['Full Name' => 'name' ,
                    'Email Address' => 'email' ,
                    'Product Name' => 'product_name' ,
                    'Amount' => 'amount' ,
                    'Status' => 'status',
                    'Creator Email' => 'sender_email'
                    ];

        $filterOperation = ['Full Name' => 'ILIKE' ,
                    'Email Address' => 'ILIKE' ,
                    'Product Name' => 'ILIKE' ,
                    'Amount' => '=' ,
                    'Status' => 'ILIKE',
                    'Creator Email' => 'ILIKE'
        ];


        return ['sortable' => $sortable , 'filterable' => $filterable , 'operation' => $filterOperation ];
    }

    public function reportSchema()
    {

        $feColumns = [
                        ['label' => 'Full Name' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Email Address' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Product Name' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Amount' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Payment URL' , 'isSort' => false , 'isFilter' => false],
                        ['label' => 'Status' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'Creator Email' , 'isSort' => true , 'isFilter' => false],
                        ['label' => 'Created At' , 'isSort' => true , 'isFilter' => false]
                     ];

        return ['headers' => $feColumns ];
    }
}
