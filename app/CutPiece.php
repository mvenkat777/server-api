<?php

namespace App;

use Platform\App\Activity\ActivityRecorder;
use Illuminate\Database\Eloquent\Model;

class CutPiece extends Model
{
    use ActivityRecorder;

    public $appName = 'techpack';
    protected $modelVerb = 'add';

    protected $fillable = [
        'id', 'techpack_id', 'name', 'image', 'amount', 'fabric', 'non_flip',
        'x', 'y', 'xy',
    ];

    protected $images = ['image'];

    protected $relation = [
        'techpack_id' => 'techpack|Platform\Techpacks\Transformers\MetaTechpackTransformer',
    ];

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
            'id' => $this->techpack->id,
            'name'=>$this->techpack->name
        ];
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'image' => 'object',
    ];

    public function techpack()
    {
        return $this->belongsTo('\App\Techpack', 'techpack_id', 'id');
    }
}
