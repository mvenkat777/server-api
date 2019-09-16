<?php

namespace app;

use Jenssegers\Mongodb\Model as Eloquent;

class DailyActionRule extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'daily_actions';

    protected $fillable = [
        'local_time','notification_via', 'allow_notify_via' ,'data', 'receiver', 'notification','email_details', 
        'slack','phone','entity'
    ];
}
