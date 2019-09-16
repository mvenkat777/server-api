<?php

namespace app;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RuleCategoryCustomRules extends Model
{
    use SoftDeletes;

    /**
     * Defining table name.
     *
     * @var table
     */
    protected $table = 'category_custom_rules';

    /**
     * Setting up the fillable columns name.
     *
     * @var array
     */
    protected $fillable = ['id', 'custom_rule_name', 'custom_rule_description', 'custom_rule'];

    /**
     * Set default incrementing to false.
     *
     * @var incrementing
     */
    public $incrementing = false;
}
