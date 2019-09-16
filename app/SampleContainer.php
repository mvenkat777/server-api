<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\SoftDeletes;

class SampleContainer extends Model
{
    use ActivityRecorder;
    use SoftDeletes;

    /**
     * Attributes that are mass assignable
     * @var array
     */
    protected $fillable = [
        'id', 'techpack_id', 'customer_id', 'style_code', 'flat_image',
        'archived_at', 'completed_at'
    ];

    protected $dates = ['deleted_at', 'archived_at', 'completed_at'];
    
    protected $images = ['flat_image'];

    public $appName = 'samplecontainer';

    protected $relation = [
        'techpack_id' => 'techpack|Platform\Techpacks\Transformers\MetaTechpackTransformer',
        'style_code' => 'style|Platform\Line\Transformers\MetaStyleTransformer',
        'customer_id' => 'customer|Platform\Customer\Transformers\MetaCustomerTransformer',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'flat_image' => 'object',
    ];

    /**
     * The attributes that are searchable
     * @var array
     */
    public $searchable = [
        'styleCode' => [
            'column' => 'style_code',
            'operation' => 'ILIKE'
        ]
    ];

    /**
     * The attributes that are sortableFields
     * @var array
     */
    public $sortable = [
        'styleCode' => 'style_code',
        'updatedAt' => 'updated_at',
    ];

    /**
     * The attributes that are searchable through a foreigh relation
     * @var array
     */
    public $foreign = [
        'techpackName' => [
            'modelField' => 'techpack_id',
            'relation' => 'App\Techpack',
            'operation' => 'ILIKE',
            'foreignField' => 'name'
        ],
        'customerName' => [
            'modelField' => 'customer_id',
            'relation' => 'App\Customer',
            'operation' => 'ILIKE',
            'foreignField' => 'name'
        ],
    ];

    public $globalSearchColumns = [];

    /**
     * get mtea for activity
     * @return array
     */
    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->techpack->name
        ];
    }

    /**
     * A sample container has many samples
     * @return mixed
     */
    public function samples()
    {
        return $this->hasMany('App\Sample', 'sample_container_id', 'id');
    }

    /**
     * A sample container has one Techpack
     * @return mixed
     */
    public function techpack()
    {
        return $this->hasOne('App\Techpack', 'id', 'techpack_id');
    }

    /**
     * A sample container has one Customer
     * @return mixed
     */
    public function customer()
    {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }

    /**
     * A sample container has one Style
     * @return mixed
     */
    public function style()
    {
        return $this->hasOne('App\Style', 'techpack_id', 'techpack_id');
    }
}
