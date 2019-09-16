<?php

namespace app;

use Jenssegers\Mongodb\Model as Eloquent;

class ImmediateActionRule extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'immediate_actions';

    protected $fillable = [
        'local_time','notification_via', 'allow_notify_via' ,'data', 'receiver', 'notification','email_details', 
        'slack','phone','entity'
    ];
}
