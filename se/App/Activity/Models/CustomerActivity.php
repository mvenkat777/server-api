<?php

namespace Platform\App\Activity\Models;

use Platform\App\Activity\Models\ParentActivity;

class CustomerActivity extends ParentActivity
{
    protected $collection = 'customer_activity';
}
