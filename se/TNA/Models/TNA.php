<?php

namespace Platform\TNA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Style;
use Platform\App\Activity\ActivityRecorder;
// use Platform\Tasks\Observers\TNAObserver;

class TNA extends Model
{
    use SoftDeletes;

    use ActivityRecorder;

    protected $table = 'tna';

    public $incrementing = false;

    public $appName = 'calendar';

    protected $fillable = [
        'id', 'title', 'creator_id', 'order_id', 'techpack_id', 
        'customer_id', 'vendor_id', 'tna_type_id', 'target_date', 
        'is_published', 'tna_state_id', 'items_order', 'customer_name',
        'customer_code', 'order_quantity', 'style_id', 'style_range',
        'style_description', 'representor_id', 'start_date', 'attachment',
        'published_date', 'projected_date', 'completed_date',
        'tna_health_id', 'is_creating_preset', 'is_publishing', 'archived_at'
    ];

    public $globalSearchColumns = ['title'];

    protected $images = ['attachment'];

    public $ignore = ['items_order'];

    protected $relation = [
        'creator_id' => 'creator|Platform\Users\Transformers\MetaUserTransformer', 
        'representor_id' => 'representor|Platform\Users\Transformers\MetaUserTransformer', 
        'techpack_id' => 'techpack|Platform\Techpacks\Transformers\MetaTechpackTransformer', 
        'order_id' => 'order|Platform\Orders\Transformers\MetaOrderTransformer', 
        'customer_id' => 'customer|Platform\Customer\Transformers\MetaCustomerTransformer', 
        'vendor_id' => 'vendor|Platform\Vendor\Transformers\MetaVendorTransformer', 
        // 'style_id' => 'style|Platform\Line\Transformers\MetaStyleTransformer', 
    ];

    protected $verbs = [];

    protected $values = [
        'tna_health_id' => 'normal|warning|danger',
        'tna_state_id' => 'draft|paused|active|completed'
    ];

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->title
        ];
    }

    public $searchable = [
            'title' => [
                'column' => 'title',
                'operation' => 'ILIKE'
            ],
            'customerName' => [
                'column' => 'customer_name',
                'operation' => 'ILIKE'
            ],
            'startDate' => [
                'column' => 'start_date',
                'operation' => 'date'
            ],
            'publishedDate' => [
                'column' => 'published_date',
                'operation' => 'date'
            ],
            'targetDate' => [
                'column' => 'target_date',
                'operation' => 'date'
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
        'title' => 'title',
        'customerName' => 'customer_name',
        'startDate' => 'start_date',
        'publishedDate' => 'published_date',
        'targetDate' => 'target_date',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public $foreign = [
        'state' => [
            'modelField' => 'tna_state_id',
            'relation' => 'Platform\TNA\Models\TNAState',
            'operation' => 'ILIKE',
            'foreignField' => 'state'
        ],
        'health' => [
            'modelField' => 'tna_health_id',
            'relation' => 'Platform\TNA\Models\TNAHealth',
            'operation' => 'ILIKE',
            'foreignField' => 'health'
        ],
        'representor' => [
            'modelField' => 'representor_id',
            'relation' => 'App\User',
            'operation' => 'ILIKE',
            'foreignField' => 'display_name'
        ]
    ];


    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }

    public function techpack()
    {
        return $this->belongsTo('App\Techpack', 'techpack_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id');
    }

    public function state()
    {
        return $this->hasOne('Platform\TNA\Models\TNAState', 'id', 'tna_state_id');
    }

    public function representor()
    {
        return $this->belongsTo('App\User', 'representor_id');
    }

    public function items()
    {
        return $this->hasMany('Platform\TNA\Models\TNAItem', 'tna_id');
    }

    public function tasks()
    {
        return $this->hasManyThrough('App\Task', 'Platform\TNA\Models\TNAItem', 'tna_id', 'tna_item_id');
    }

    public function health()
    {
        return $this->hasOne('Platform\TNA\Models\TNAHealth', 'id', 'tna_health_id');
    }

    public function line()
    {
        $style = $this->style();
        if(!is_null($style)) {
            return $style->line;
        }
        return null;
    }

    public function style()
    {
        return Style::where('tna_id', $this->id)->first();
    }

    public function streamStyle()
    {
        return $this->hasOne('App\Style', 'tna_id');
    }
    public function transformSchema(){

        $sortable = [
                'Title' => 'title' , 
                'CustomerName' => 'customer_name',
                'customerCode' => 'customer_code',
                'Owner' => 'representor_id',
                'StartDate' => 'start_date' ,
                'TargetDate' => 'target_date',
                'PublishedDate' => 'published_date',
                'ProjectedDate' => 'projected_date',
                'CompletedDate' => 'completed_date',
                'State' => 'tna_state_id',
                'Status'   => 'tna_health_id',
                // 'Created At' => 'created_at' ,
                // 'Updated At' => 'updated_at'
        ];

        $filterable = [
                'Title' => 'title' ,
                'CustomerName' => 'customer_name',
                'CustomerCode' => 'customer_code',
                'Owner' => 'representor_id',
                'State' => 'tna_state_id', 
                'Status'   => 'tna_health_id',
                'StartDate' => 'start_date' ,
                'TargetDate' => 'target_date',
                'PublishedDate' => 'published_date',
                'ProjectedDate' => 'projected_date',
                'CompletedDate' => 'completed_date'
        ];

        $filterOperation = [
                'Title' => 'ILIKE' , 
                'CustomerName' => 'ILIKE' ,
                'CustomerCode' => 'ILIKE',
                'TargetDate' => '=', 
                'Status' => '=',
                'State' => '=',
                'PublishedDate' => '=',
                'ProjectedDate' => '=',
                'CompletedDate' => '=',
                'StartDate' => '='
        ];
        
        return ['sortable' => $sortable , 'filterable' => $filterable , 'operation' => $filterOperation ];
    }

    public function reportSchema(){

        $feColumns = [ 
                        ['label' => 'Title' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'CustomerName' , 'isSort' => true , 'isFilter' => true],
                        ['label' => 'StyleCode' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'State' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Status' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'Owner' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'StartDate' , 'isSort' => false , 'isFilter' => true],
                        ['label' => 'TargetDate' , 'isSort' => true , 'isFilter' => false],
                        ['label' => 'Created At' , 'isSort' => true , 'isFilter' => false]
                     ];
        
        //return ['headers' => $feColumns , 'sortable' => ['orderby' => $sortable] , 'filterable' => ['type' => $filterable] , 'paginate' => $paginate ];
        return ['headers' => $feColumns ];
    }

        public static function table() {
            $model = new static;
            return $model->getTable();
        }

        public static function boot() {
            $events = new \Illuminate\Events\Dispatcher;
            static::observe(new \Platform\Observers\TNA\TNAObserver);
            parent::boot($events);
        }
}
