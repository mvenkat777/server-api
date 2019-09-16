<?php
namespace Platform\App\Activity\Models;
use Jenssegers\Mongodb\Model as Eloquent;
class NotificationReceiver extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'notification_receiver';

    protected $fillable = [
        'user', 'object'
    ];
}
