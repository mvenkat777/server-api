<?php

namespace Platform\App\Activity\Models;

use Platform\App\Activity\Models\ParentActivity;

class LineListProductStream extends ParentActivity
{
    protected $collection = 'line_list_product_stream';

    public $fillable = ['line_id', 'name', 'last_updated', 'count'];

    public $timestamps = ['last_updated'];
}