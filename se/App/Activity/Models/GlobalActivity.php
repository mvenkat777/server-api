<?php

namespace Platform\App\Activity\Models;

use Platform\App\Activity\Models\ParentActivity;

class GlobalActivity extends ParentActivity
{
    protected $collection = 'global_activity';
}
