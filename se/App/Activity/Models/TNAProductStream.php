<?php

namespace Platform\App\Activity\Models;

use Platform\App\Activity\Models\ParentActivity;

class TNAProductStream extends ParentActivity
{
    protected $collection = 'tna_product_stream';

    public $fillable = ['meta', 'stream'];
}