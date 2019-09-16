<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Platform\App\Activity\ActivityRecorder;

class SampleCriteria extends Model
{
    use ActivityRecorder;

    /**
     * Fields that are mass assignable
     * @var array
     */
    protected $fillable = [
        'id', 'sample_id', 'criteria', 'description', 'note',
    ];

    public $appName = 'sample';
    public $modelVerb = 'add';


    protected $relation = [
        'sample_id' =>'sample|Platform\SampleContainer\Transformers\MetaSampleTransformer',
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
            'name'=>$this->criteria
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
            'id' => $this->sample->id,
            'name'=>$this->sample->title,
            'parentId' => $this->sample->sampleContainer->id,
            'parentName'=>$this->sample->sampleContainer->techpack->name
        ];
    }

    /**
     * A sample has one sample
     * @return mixed
     */
    public function sample()
    {
        return $this->hasOne('App\Sample', 'id', 'sample_id');
    }

    /**
     * A sample criteria has many attachments
     * @return mixed
     */
    public function attachments()
    {
        return $this->hasMany('App\SampleCriteriaAttachment', 'sample_criteria_id', 'id');
    }

    /**
     * A sample criteria has many comments
     * @return mixed
     */
    public function comments()
    {
        return $this->hasMany('App\SampleCriteriaComment', 'sample_criteria_id', 'id');
    }
}
