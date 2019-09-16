<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Platform\App\Activity\ActivityRecorder;
use Platform\App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sample extends BaseModel
{
    use SoftDeletes;
    /**
     * Fields that are mass assignable
     * @var array
     */
    protected $fillable = [
        'id', 'sample_container_id', 'title', 'type', 'author_id', 'image',
        'sent_date', 'received_date', 'vendor_id', 'weight_or_quality',
        'fabric_or_content', 'pom', 'action_forward', 'archived_at', 'completed_at'
    ];

    protected $dates = ['deleted_at', 'archived_at', 'completed_at'];
    protected $relation = [
        'sample_container_id' =>'sampleContainer|Platform\SampleContainer\Transformers\MetaSampleContainerTransformer',
        'author_id' => 'author|Platform\Users\Transformers\MetaUserTransformer',
        'vendor_id' => 'vendor|Platform\Vendor\Transformers\MetaVendorTransformer',
    ];

    public $appName = 'samplecontainer';

    public $ignore = ['pom'];

    protected $images = ['image'];

    public $modelVerb = 'add';

    /**
     * Fields to be casted to native types
     * @var array
     */
    protected $casts = [
        'image' => 'object',
        'pom' => 'object',
    ];

    /**
     * get sample meta for activityModel
     *
     * @return  array
     */
    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->title,
            'parentId' => $this->sampleContainer->id,
            'parentName'=>$this->sampleContainer->techpack->name
        ];
    }

    /**
     * get sample parentmeta for activity
     *
     * @return  array
     */
    public function getParentMeta()
    {
        return [
            'id' => $this->sampleContainer->id,
            'name'=>$this->sampleContainer->techpack->name
        ];
    }

    /**
     * A sample has one sample container
     * @return mixed
     */
    public function sampleContainer()
    {
        return $this->hasOne('App\SampleContainer', 'id', 'sample_container_id');
    }

    /**
     * A sample has one author
     * @return mixed
     */
    public function author()
    {
        return $this->hasOne('App\User', 'id', 'author_id');
    }

    /**
     * A sample has one vendor
     * @return mixed
     */
    public function vendor()
    {
        return $this->hasOne('App\Vendor', 'id', 'vendor_id');
    }

    /**
     * A sample has many criterias
     * @return mixed
     */
    public function criterias()
    {
        return $this->hasMany('App\SampleCriteria', 'sample_id', 'id');
    }

}
