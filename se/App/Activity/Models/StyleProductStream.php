<?php

namespace Platform\App\Activity\Models;

use Platform\App\Activity\Models\ParentActivity;

class StyleProductStream extends ParentActivity
{
    protected $collection = 'style_product_stream';

    public $fillable = ['meta', 'stream'];
}