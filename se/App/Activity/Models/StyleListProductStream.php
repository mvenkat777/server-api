<?php

namespace Platform\App\Activity\Models;

use Platform\App\Activity\Models\ParentActivity;

class StyleListProductStream extends ParentActivity
{
    protected $collection = 'style_list_product_stream';

    public $fillable = ['line_id', 'style_id', 'style_name', 'last_updated'];

    public $timestamps = ['last_updated'];
}