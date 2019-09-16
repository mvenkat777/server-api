<?php

namespace Platform\App\Activity\Models;

use Platform\App\Activity\Models\ParentActivity;

class TechpackProductStream extends ParentActivity
{
    protected $collection = 'techpack_product_stream';

    public $fillable = ['meta', 'stream'];
}