<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Platform\App\Activity\ActivityRecorder;

class SampleCriteriaComment extends Model
{
    use ActivityRecorder;

    /**
     * Fields that are mass assignable
     * @var array
     */
    protected $fillable = [
        'id', 'sample_criteria_id', 'comment', 'commenter_id'
    ];

    public $appName = 'sample';
    public $modelVerb = 'comment';


    protected $relation = [
        'sample_criteria_id' =>'sampleCriteria|Platform\SampleContainer\Transformers\SampleCriteriaTransformer',
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
            'name'=>$this->comment
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
     * A sample criteria comment has one sampleCriteria
     * @return mixed
     */
    public function sampleCriteria()
    {
        return $this->hasOne('App\SampleCriteria', 'id', 'sample_criteria_id');
    }

    /**
     * A sample criteria comment has one commenter
     * @return mixed
     */
    public function commenter()
    {
        return $this->hasOne('App\User', 'id', 'commenter_id');
    }
}
