<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PomSheet extends Model
{
	use SoftDeletes;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'archived_at'];
    protected $fillable = ['id', 'pom_id', 'key', 'qc', 'description', 'tol', 'code', 'data', 'archived_at'];

    protected $table = 'pom_sheets';

    public $searchable = [
        'code' => [
            'column' => 'code',
            'operation' => '='
        ]
    ];
}
