<?php

namespace app;

use Jenssegers\Mongodb\Model as Eloquent;

class FrameworkLog extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'logs_collection';

    protected $fillable = [
        'type','createdAt','logType','app','UA','request','response',
    ];
}
