<?php

namespace App;

use App\StyleDevelopment;
use App\StyleProduction;
use App\StyleShipped;
// use Illuminate\Database\Eloquent\Model;
use Platform\App\Models\BaseModel;
use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Style extends BaseModel
{
    // use ActivityRecorder;
	use SoftDeletes;

	/**
	 * The mass assignable fields
	 *
	 * @var array
	 * @access protected
	 */
	protected $fillable = [
            'id', 'code', 'name', 'line_id', 'tna_id', 'techpack_id',
            'sample_submission_id', 'order_id', 'flat', 'customer_style_code',
            'product_brief', 'archived_at', 'completed_at'
	];

    protected $dates = ['archived_at', 'deleted_at', 'completed_at'];
    
    protected $relation = ['line_id' => 'line|Platform\Line\Transformers\MetaLineTransformer',
        'tna_id' => 'tna|Platform\TNA\Transformers\MetaTNATransformer',
        'techpack_id' => 'techpack|Platform\Techpacks\Transformers\MetaTechpackTransformer',
        'order_id' => 'order|Platform\Orders\Transformers\MetaOrderTransformer'
    ];

    protected $jsonFields = ['product_brief'];
    protected $images = ['flat', 'images'];
    protected $ignore = ['product_brief'];

    public $appName = 'line';
    public $notificationName = 'style';
    protected $modelVerb = 'add';
    protected $verbs = [
    ];



    protected $childFields = ['techpack_id' => 'techpacks|techpack', 'order_id' => 'orders|order'];

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
            'id' => $this->line->id,
            'name'=>$this->line->name
        ];
    }


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'flat' => 'object',
    ];


    /**
     * Get techpack of the style
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function techpack()
    {
    	return $this->hasOne('App\Techpack', 'id', 'techpack_id');
    }

    /**
     * Get tna of the style
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tna()
    {
    	return $this->hasOne('Platform\TNA\Models\TNA', 'id', 'tna_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sampleSubmissions()
    {
        return $this->belongsToMany(
            'App\SampleSubmission',
            'sample_submission_style',
            'style_id',
            'sample_submission_id'
        );
    }

    /**
     * A style has one sample container
     * @return mixed
     */
    public function sampleContainer()
    {
        return $this->hasOne(
            'App\SampleContainer',
            'techpack_id',
            'techpack_id'
        );
    }

    /**
     * Get order of the style
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order()
    {
    	return $this->hasOne('App\Order', 'id', 'order_id');
	}

    /**
     * Get line of the style
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function line()
    {
        return $this->belongsTo('App\Line', 'line_id', 'id');
    }

    /**
     * @return App\StyleDevelopment
     */
    public function development()
    {
        return $this->belongsToMany('App\StyleDevelopment',
            'style_development_style',
            'style_id', 'style_development_id'
        )->withPivot(['owner','is_approved', 'approved_at',
            'approved_by','is_enabled',
            'unapproved_at', 'unapproved_by'
        ])->orderBy('style_development_id');
    }

    /**
     * @return App\StyleProduction
     */
    public function production()
    {
        return $this->belongsToMany('App\StyleProduction',
            'style_production_style',
            'style_id', 'style_production_id'
        )->withPivot(['owner','is_approved', 'approved_at',
            'approved_by','is_enabled',
            'unapproved_at', 'unapproved_by'
        ])->orderBy('style_production_id');
    }

    /**
     * @return App\Styleshipped
     */
    public function shipped()
    {
        return $this->belongsToMany('App\StyleShipped',
            'style_shipped_style',
            'style_id', 'style_shipped_id'
        )->withPivot(['owner','is_approved', 'approved_at',
            'approved_by','is_enabled',
            'unapproved_at', 'unapproved_by'
        ])->orderBy('style_shipped_id');
    }

    /**
     * @return App\Styleshipped
     */
    public function review()
    {
        return $this->belongsToMany('App\StyleReview',
            'style_review_style',
            'style_id', 'style_review_id'
        )->withPivot(['owner','is_approved', 'approved_at',
            'approved_by','is_enabled',
            'unapproved_at', 'unapproved_by'
        ])->orderBy('style_review_id');
    }
}
