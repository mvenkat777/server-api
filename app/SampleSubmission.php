<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Platform\Observers\SampleSubmission\SampleSubmissionObserver;

class SampleSubmission extends Model
{
    protected $fillable = ['id', 'name', 'user_id', 'style_code', 'sent_date', 'received_date', 'type', 'content',
        'weight', 'vendor', 'customer_id', 'techpack_id',
    ];

    public $searchable = [
        'name' => [
            'column' => 'name',
            'operation' => 'ILIKE',
        ],
        'vendor' => [
            'column' => 'vendor',
            'operation' => 'ILIKE',
        ],
    ];

    public $foreign = [
        'customer' => [
            'modelField' => 'customer_id',
            'relation' => 'App\Customer',
            'operation' => 'ILIKE',
            'foreignField' => 'name'
        ],
        'techpack' => [
            'modelField' => 'techpack_id',
            'relation' => 'App\Techpack',
            'operation' => 'ILIKE',
            'foreignField' => 'name'
        ],
        'author' => [
            'modelField' => 'user_id',
            'relation' => 'App\User',
            'operation' => 'ILIKE',
            'foreignField' => 'display_name'
        ]
    ];

    public $sortable = [
        'name' => 'name',
        'receivedDate' => 'received_date',
        'sentDate' => 'sent_date',
        'vendor' => 'vendor',
    ];

    public $incrementing = false;

    /**
     * Categories relation for Sample Submission
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany('App\SampleSubmissionCategory', 'sample_submission_id');
    }

    public function transformSchema()
    {
        $sortable = [
            'Name' => 'name',
            'SentDate' => 'sent_date',
            'ReceivedDate' => 'received_date',
            'Weight' => 'weight',
            'CreatedAt' => 'created_at' ,
            'UpdatedAt' => 'updated_at',
        ];

        $filterable = [
            'Name' => 'name',
            'StyleCode' => 'style_code',
            'SentDate' => 'sent_date',
            'ReceivedDate' => 'received_date',
            'Vendor' => 'vendor',
        ];

        $filterOperation = [
            'Name' => 'ILIKE',
            'StyleCode' => 'ILIKE',
            'SentDate' => '=',
            'ReceivedDate' => '=',
            'Vendor' => 'ILIKE',
        ];

        return [
            'sortable' => $sortable ,
            'filterable' => $filterable ,
            'operation' => $filterOperation
        ];
    }

    public function reportSchema()
    {
        $feColumns = [
            [
                'label' => 'Name' ,
                'isSort' => true,
                'isFilter' => true
            ],
            [
                'label' => 'StyleCode' ,
                'isSort' => false,
                'isFilter' => true
            ],
            [
                'label' => 'SentDate' ,
                'isSort' => true,
                'isFilter' => false
            ],
            [
                'label' => 'ReceivedDate' ,
                'isSort' => true,
                'isFilter' => false
            ],
            [
                'label' => 'Weight' ,
                'isSort' => true,
                'isFilter' => false
            ],
            [
                'label' => 'Vendor' ,
                'isSort' => false,
                'isFilter' => true
            ],
        ];

        return ['headers' => $feColumns ];
    }

    public static function table() {
        $model = new static;
        return $model->getTable();
    }

    public static function boot() {
        $events = new \Illuminate\Events\Dispatcher;
        static::observe(new SampleSubmissionObserver);
        parent::boot($events);
    }
}
