<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Platform\App\Models\BaseModel;
use Platform\App\Activity\ActivityRecorder;

class Colorway extends BaseModel
{
    // use ActivityRecorder;

    public $appName = 'techpack';
    protected $modelVerb = 'add';

    /**
     * We use uuids
     * @var boolean
     */
    public $incrementing = false;

    protected $fillable = [
        'id', 'techpack_id', 'bom_line_item_id', 'approval', 'colorway'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'approval' => 'object',
        'colorway' => 'object',
    ];

    protected $ignore = ['bom_line_item_id', 'colorway', 'approval'];

    protected $relation = [
        'techpack_id' => 'techpack|Platform\Techpacks\Transformers\MetaTechpackTransformer',
    ];

    public function getMeta()
    {
        return [
            'id' => $this->id,
            'name'=>'colorway'
        ];
    }

    public function getParentMeta()
    {
        return [
            'id' => $this->techpack->id,
            'name'=>$this->techpack->name
        ];
    }

    public function techpack()
    {
        return $this->belongsTo('\App\Techpack', 'techpack_id', 'id');
    }
}
