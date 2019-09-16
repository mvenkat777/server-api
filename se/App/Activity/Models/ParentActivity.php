<?php

namespace Platform\App\Activity\Models;

use Jenssegers\Mongodb\Model as Eloquent;

class ParentActivity extends Eloquent
{
    public $connection = 'mongodb';

    public $fillable = [
        'version','objectType', 'status' ,'rules', 'entity', 'actor','verb', 
        'links','published'
    ];
}
