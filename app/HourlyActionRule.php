<?php

namespace app;

use Jenssegers\Mongodb\Model as Eloquent;

class HourlyActionRule extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'hourly_actions';

    protected $fillable = [
        'local_time','notification_via', 'allow_notify_via', 'data', 'receiver', 'email_details', 
        'entity'
    ];
}
