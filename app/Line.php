<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Platform\App\Models\BaseModel;
// use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Line extends BaseModel
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
		'id', 'customer_id', 'sales_representative_id', 'production_lead_id', 'so_target_date', 'delivery_target_date',
        'code', 'product_development_lead_id', 'name', 'merchandiser_id', 'targetCustomer', 'fitReference', 'category',
        'styles_count', 'vlp_attachments', 'archived_at', 'completed_at'
	];

    public $appName = 'line';
    public $notificationName = 'line';

    protected $relation = [
        'sales_representative_id' => 'salesRepresentative|Platform\Users\Transformers\MetaUserTransformer',
        'production_lead_id' => 'productionLead|Platform\Users\Transformers\MetaUserTransformer',
        'merchandiser_id' => 'merchandiser|Platform\Users\Transformers\MetaUserTransformer',
        'product_development_lead_id' => 'productDevelopmentLead|Platform\Users\Transformers\MetaUserTransformer',
        'customer_id' => 'customer|Platform\Customer\Transformers\MetaCustomerTransformer'
    ];

    public $globalSearchColumns = ['name'];

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->name
        ];
    }

	/**
	 * The date fields
	 *
	 * @var array
	 */
	protected $dates = [
		'deleted_at', 'so_target_date', 'delivery_target_date', 'archived_at', 'completed_at'
	];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'vlp_attachments' => 'object',
    ];


	/**
	 * The searchable fields
	 *
	 * @var array
	 */
	public $searchable = [
        'code' => [
            'column' => 'code',
            'operation' => 'ILIKE'
        ],
        'name' => [
            'column' => 'name',
            'operation' => 'ILIKE'
        ],
        'soTargetDate' => [
            'column' => 'so_target_date',
            'operation' => 'date'
        ],
        'deliveryTargetDate' => [
            'column' => 'delivery_target_date',
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

    /**
     * The sortable fields
     *
     * @var array
     */
    public $sortable = [
        'code' => 'code',
        'name' => 'name',
        'soTargetDate' => 'so_target_date',
        'deliveryTargetDate' => 'delivery_target_date',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    /**
     * The fields having foreign relationships
     *
     * @var array
     */
    public $foreign = [
        'customer' => [
            'modelField' => 'customer_id',
            'relation' => 'App\Customer',
            'operation' => 'ILIKE',
            'foreignField' => 'name'
        ],
        'salesRepresentative' => [
            'modelField' => 'sales_representative_id',
            'relation' => 'App\User',
            'operation' => 'ILIKE',
            'foreignField' => 'display_name'
        ],
        'productionLead' => [
            'modelField' => 'production_lead_id',
            'relation' => 'App\User',
            'operation' => 'ILIKE',
            'foreignField' => 'display_name'
        ],
        'productDevelopmentLead' => [
            'modelField' => 'product_development_lead_id',
            'relation' => 'App\User',
            'operation' => 'ILIKE',
            'foreignField' => 'display_name'
        ],
        'merchandiser' => [
            'modelField' => 'merchandiser_id',
            'relation' => 'App\User',
            'operation' => 'ILIKE',
            'foreignField' => 'display_name'
        ]
    ];

    /**
     * Get all styles related to this line
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function styles() {
        return $this->hasMany('App\Style', 'line_id', 'id');
    }

    /**
     * productionLead
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function productionLead()
    {
    	return $this->hasOne('App\User', 'id', 'production_lead_id');
    }

    /**
     * A line has many ALPAtta chmentApprovals
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function VLPAttachmentApprovals()
    {
        return $this->hasMany('App\VLPAttachmentApproval', 'line_id', 'id')
                    ->with(['approver' => function ($query) {
                        $query->select('id', 'display_name as displayName', 'email')
                              ->first();
                    }]);
    }
    /**
     * Get product development lead
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function productDevelopmentLead()
    {
    	return $this->hasOne('App\User', 'id', 'product_development_lead_id');
    }

    /**
     * salesRepresentative
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function salesRepresentative()
    {
    	return $this->hasOne('App\User', 'id', 'sales_representative_id');
    }

    /**
     * get the merchandiser for the line
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function merchandiser()
    {
    	return $this->hasOne('App\User', 'id', 'merchandiser_id');
    }

    /**
     * customer
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer()
    {
    	return $this->hasOne('App\Customer', 'id', 'customer_id');
    }
}
