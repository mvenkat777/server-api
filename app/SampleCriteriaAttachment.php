<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Platform\App\Activity\ActivityRecorder;

class SampleCriteriaAttachment extends Model
{
    use ActivityRecorder;

    /**
     * Fields that are mass assignable
     * @var array
     */
    protected $fillable = [
        'id', 'sample_criteria_id', 'file', 'uploader_id'
    ];

    public $appName = 'sample';
    public $modelVerb = 'attach';


    protected $relation = [
        'sample_criteria_id' =>'sampleCriteria|Platform\SampleContainer\Transformers\SampleCriteriaTransformer',
    ];


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'file' => 'object',
    ];

     /**
     * get sample meta for activity
     *
     * @return  array
     */
    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>$this->file->title
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
            'id' => $this->sampleCriteria->sample->id,
            'name'=>$this->sampleCriteria->sample->title,
            'parentId' => $this->sampleCriteria->sample->sampleContainer->id,
            'parentName'=>$this->sampleCriteria->sample->sampleContainer->techpack->name
        ];
    }

    /**
     * A sample criteria attachment has one sampleCriteria
     * @return mixed
     */
    public function sampleCriteria()
    {
        return $this->hasOne('App\SampleCriteria', 'id', 'sample_criteria_id');
    }

    /**
     * A sample criteria attachment has one uploader
     * @return mixed
     */
    public function uploader()
    {
        return $this->hasOne('App\User', 'id', 'uploader_id');
    }
}
