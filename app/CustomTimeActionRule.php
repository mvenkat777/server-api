<?php

namespace app;

use Jenssegers\Mongodb\Model as Eloquent;

class CustomTimeActionRule extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'custom_time_actions';

    protected $fillable = [
        'local_time','notification_via', 'allow_notify_via', 'data', 'receiver', 'email_details', 
        'entity'
    ];
}
