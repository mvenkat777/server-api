<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'materials';

    protected $fillable = [
        'id', 'material_reference_no', 'material_type', 'construction', 'construction_type',
        'fabric_type','fiber_1','fiber_1_percentage','fiber_2','fiber_2_percentage','fiber_3','fiber_3_percentage','other_fibers','weight','weight_uom','cuttable_width','width_uom'
    ];

    public function library()
    {
        return $this->hasMany('App\MaterialLibrary','material_id','id')->whereNull('deleted_at');
        
    }


    public $searchable = [
        'materialReferenceNo' => [
            'column' => 'material_reference_no',
            'operation' => 'ILIKE'
        ],
        'materialType' => [
            'column' => 'material_type',
            'operation' => 'ILIKE'
        ],
        'construction' => [
            'column' => 'construction',
            'operation' => 'ILIKE'
        ],
        'constructionType' => [
            'column' => 'construction_type',
            'operation' => 'ILIKE'
        ],
        'fabricType' => [
            'column' => 'fabric_type',
            'operation' => 'ILIKE'
        ],
        'fiber1' => [
            'column' => 'fiber_1',
            'operation' => 'ILIKE'
        ],
        'fiber2' => [
            'column' => 'fiber_2',
            'operation' => 'ILIKE'
        ],
        'fiber3' => [
            'column' => 'fiber_3',
            'operation' => 'ILIKE'
        ],
        'xfootCheck' => [
            'column' => 'xfoot_check',
            'operation' => '='
        ],
        'weight' => [
            'column' => 'weight',
            'operation' => '='
        ],
        'weightUom' => [
            'column' => 'weight_uom',
            'operation' => 'ILIKE'
        ],
        'cuttableWidth' => [
            'column' => 'cuttable_width',
            'operation' => '='
        ],
        'widthUom' => [
            'column' => 'width_uom',
            'operation' => 'ILIKE'
        ]
    ];

    public $sortable = [
        'materialReferenceNo' => 'material_reference_no',
        'materialType' => 'material_type',
        'construction' => 'construction',
        'constructionType' => 'construction_type',
        'fabricType' => 'fabric_type',
        'fiber1' => 'fiber_1',
        'fiber1Percentage' => 'fiber_1_percentage',
        'fiber2' => 'fiber_2',
        'fiber2Percentage' => 'fiber_2_percentage',
        'fiber3' => 'fiber_3',
        'fiber3Percentage' => 'fiber_3_percentage',
        'xfootCheck' => 'xfoot_check',
        'weight' => 'weight',
        'weightUom' => 'weightUom',
        'cuttableWidth' => 'cuttable_width',
        'widthUom' => 'width_uom',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];
    
}
