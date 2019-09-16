<?php

namespace Platform\App\Activity\Models;

use Platform\App\Activity\Models\ParentActivity;

class SampleProductStream extends ParentActivity
{
    protected $collection = 'sample_product_stream';

    public $fillable = ['meta', 'stream'];
}