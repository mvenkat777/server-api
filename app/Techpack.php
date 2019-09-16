<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\Techpacks\Transformers\ColorwaysTransformer;
use Platform\App\Models\BaseModel;
// use Platform\App\Activity\ActivityRecorder;
use App\Colorway;

/**
 * Class Techpack.
 */
class Techpack extends BaseModel
{
    // use ActivityRecorder;

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'techpacks';
    /**
     * @var bool
     */
    public $incrementing = false;


    public $appName = 'techpack';

    public $action = 'created';

    protected $verbs = [

    ];

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->name
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'version', 'name', 'style_code', 'category', 'season', 'stage',
        'revision', 'visibility', 'image', 'state','is_published',
        'is_builder_techpack', 'meta', 'bill_of_materials', 'spec_sheets',
        'color_sets', 'sketches', 'parent_id', 'user_id', 'data', 'product',
        'product_type', 'size_type', 'poms', 'customer_id', 'archived_at',
        'locked_at', 'locked_by', 'unlocked_at', 'unlocked_by', 'completed_at',
    ];
    protected $dates = ['deleted_at', 'archived_at', 'completed_at'];

    protected $relation = [
        'user_id' => 'owner|Platform\Users\Transformers\MetaUserTransformer',
        'customer_id' => 'customer|Platform\Customer\Transformers\MetaCustomerTransformer'
    ];

    protected $ignore = [
        'meta', 'data', 'bill_of_materials', 'sketches', 'poms',
        'is_builder_techpack', 'revision', 'spec_sheets'
    ];
    // protected $jsonFields = ['bill_of_materials', 'sketches', 'poms'];

    protected $images = ['image'];

    protected $document = [];

    public $searchable = [
        'name' => [
            'column' => 'name',
            'operation' => 'ILIKE',
        ],
        'styleCode' => [
            'column' => 'style_code',
            'operation' => 'ILIKE',
        ],
        'category' => [
            'column' => 'category',
            'operation' => 'ILIKE',
        ],
        'season' => [
            'column' => 'season',
            'operation' => 'ILIKE',
        ],
        'stage' => [
            'column' => 'stage',
            'operation' => '=',
        ],
        'public' => [
            'column' => 'visibility',
            'operation' => '=',
        ],
        'updatedAt' => [
            'column' => 'updated_at',
            'operation' => 'date'
        ],
		'customer' => [
			'column' => "meta->'customer'->>'name'",
			'operation' => 'ILIKE',
		],
    ];

    public $foreign = [
        'userName' => [
            'modelField' => 'user_id',
            'relation' => 'App\User',
            'operation' => 'ILIKE',
            'foreignField' => 'display_name'
        ]
    ];

    public $sortable = [
        'name' => 'name',
        'category' => 'category',
        'season' => 'season',
        'stage' => 'stage',
        'updatedAt' => 'updated_at',
		'customer' => "meta->>'customer'",
    ];

    public $metaFields = ['id', 'name', 'version', 'meta', 'user_id', 'parent_id', 'created_at', 'updated_at', 'deleted_at'];

    public $globalSearchColumns = ['name', 'style_code'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public static $rules = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'object',
        'image' => 'object',
        'bill_of_materials' => 'object',
        'spec_sheets' => 'object',
        'color_sets' => 'object',
        'sketches' => 'object',
        'data' => 'object',
        'poms' => 'object',
    ];

    /**
     * @return Illuminate\Database\Eloquent\belongsToMany
     */
    public function customer()
    {
        return $this->hasOne('\App\Customer', 'id', 'customer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsToMany(
            'App\User',
            'techpack_user',
            'techpack_id',
            'user_id'
        )->withPivot('permission');
    }

    /**
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\TechpackComment', 'techpack_id', 'id');
    }

    /**
     * Get owner of the techpack.
     *
     * @return Illuminate\Database\Eloquent\belongsToMany
     */
    public function owner()
    {
    	return $this->hasOne('App\User', 'id', 'user_id');
    }

    /**
     * Techpack has one sampleContainer
     * @return App\SampleContainer
     */
    public function sampleContainer()
    {
        return $this->hasOne('App\SampleContainer', 'techpack_id', 'id');
    }

    /**
     * @return TNA
     */
    public function TNA()
    {
        return $this->hasMany('Platform\TNA\Models\TNA', 'techpack_id', 'id');
    }

    /**
     * @return Cutticket
     */
    public function cutTickets()
    {
        return $this->hasMany('App\CutPiece', 'techpack_id', 'id')->orderBy('updated_at');
    }

    /**
     * @return  TechpackCutTicketNote
     */
    public function cutTicketNote()
    {
        return $this->hasOne('App\TechpackCutTicketNote', 'techpack_id', 'id');
    }

    /**
     * @return Style
     */
    public function style()
    {
        return $this->belongsTo('App\Style', 'id', 'techpack_id');
    }

    /**
     * Return bill of material items with colorways
     * @param  string $value
     * @return object
     */
    public function getBillOfMaterialsAttribute($value)
    {
        $fractal = new \League\Fractal\Manager();
		$techpackId = $this->attributes['id'];

        $boms = json_decode($value);
        if ($boms) {
            foreach ($boms as $bom) {
                foreach ($bom->rows as $bomLine) {
					$colorway = Colorway::where('bom_line_item_id', $bomLine->id)
										  ->where('techpack_id', $techpackId)
										  ->first();
					if ($colorway) {
						$bomLine->colorway = (new ColorwaysTransformer())->transform($colorway);
					} else {
						$bomLine->colorway = [];
					}
                }
            }
        }
        unset($boms->colorways);
        return $boms;
    }


	/**
	 * Remove the colorways from bill of materials before inserting
	 *
	 * @param array $boms
	 * @return array
	 */
	public function setBillOfMaterialsAttribute($boms) {
		foreach ($boms as &$bom) {
			if (is_array($bom)) {
				foreach ($bom['rows'] as $key => &$bomLineItem) {
					unset($bomLineItem['colorway']);
				}
			}
		}
		$this->attributes['bill_of_materials'] = json_encode($boms);
	}


    public function transformSchema()
    {
        $sortable = [
            'Name' => 'name',
            'Season' => 'season',
            'Stage' => 'stage',
            'Visibility' => 'visibility',
            'Product' => 'product',
            'CreatedAt' => 'created_at' ,
            'UpdatedAt' => 'updated_at',
        ];

        $filterable = [
            'Name' => 'name',
            'StyleCode' => 'style_code',
            'Category' => 'category',
            'Season' => 'season',
            'Stage' => 'stage',
            'State' => 'state',
            'Product' => 'product',
            'SizeType' => 'size_type',
        ];

        $filterOperation = [
            'Name' => 'ILIKE',
            'StyleCode' => 'ILIKE',
            'Category' => 'ILIKE',
            'Season' => 'ILIKE',
            'Stage' => '=',
            'State' => 'ILIKE',
            'Product' => 'ILIKE',
            'SizeType' => 'ILIKE',
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
                'isSort' => true ,
                'isFilter' => true
            ],
            [
                'label' => 'StyleCode' ,
                'isSort' => false ,
                'isFilter' => true
            ],
            [
                'label' => 'Category' ,
                'isSort' => false ,
                'isFilter' => true
            ],
            [
                'label' => 'Season' ,
                'isSort' => true ,
                'isFilter' => true
            ],
            [
                'label' => 'Stage' ,
                'isSort' => true ,
                'isFilter' => true
            ],
            [
                'label' => 'Visibility' ,
                'isSort' => true ,
                'isFilter' => false
            ],
            [
                'label' => 'State' ,
                'isSort' => false ,
                'isFilter' => true
            ],
            [
                'label' => 'Product' ,
                'isSort' => true ,
                'isFilter' => true
            ],
            [
                'label' => 'SizeType' ,
                'isSort' => false ,
                'isFilter' => true
            ],
            [
                'label' => 'CreatedAt' ,
                'isSort' => true ,
                'isFilter' => false
            ],
            [
                'label' => 'UpdatedAt' ,
                'isSort' => true ,
                'isFilter' => false
            ]
        ];

        return ['headers' => $feColumns ];
    }

    public static function table() {
        $model = new static;
        return $model->getTable();
    }

    public static function boot() {
        $events = new \Illuminate\Events\Dispatcher;
        static::observe(new \Platform\Observers\Techpack\TechpackObserver);
        parent::boot($events);
    }
}
