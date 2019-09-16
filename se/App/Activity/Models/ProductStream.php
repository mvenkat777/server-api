<?php

namespace Platform\App\Activity\Models;

use Platform\App\Activity\Models\ParentActivity;

class ProductStream extends ParentActivity
{
    protected $collection = 'product_stream';

    public $fillable = ['stream', 'count'];
}