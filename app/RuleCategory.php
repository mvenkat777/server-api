<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RuleCategory extends Model
{
    use SoftDeletes;

    /**
     * Guarding the id column.
     *
     * @var guarded
     */
    protected $guarded = ['id'];
    
    protected $hidden = ['deleted_at'];

    /**
     * Defining table name.
     *
     * @var table
     */
    protected $table = 'rules_category_name';

    /**
     * Setting up the fillable columns name.
     *
     * @var array
     */
    protected $fillable = ['id', 'category_name'];

    /**
     * Set default incrementing to false.
     *
     * @var incrementing
     */
    public $incrementing = false;
}
