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
    protected $table = 'category_rules';

    /**
     * Setting up the fillable columns name.
     *
     * @var array
     */
    protected $fillable = ['id', 'rule_name', 'rule_description', 'default_rule'];

    /**
     * Set default incrementing to false.
     *
     * @var incrementing
     */
    public $incrementing = false;
}
