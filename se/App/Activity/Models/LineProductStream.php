<?php

namespace Platform\App\Activity\Models;

use Platform\App\Activity\Models\ParentActivity;

class LineProductStream extends ParentActivity
{
    protected $collection = 'line_product_stream';

    public $fillable = ['meta', 'stream'];
}