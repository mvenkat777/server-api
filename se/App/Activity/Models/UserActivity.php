<?php

namespace Platform\App\Activity\Models;

use Platform\App\Activity\Models\ParentActivity;

class UserActivity extends ParentActivity
{
    protected $collection = 'user_activity';
}
