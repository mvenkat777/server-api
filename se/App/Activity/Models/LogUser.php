<?php

namespace Platform\App\Activity\Models;

use Jenssegers\Mongodb\Model as Eloquent;

class LogUser extends Eloquent
{
    public $connection = 'mongodb';

    protected $collection = 'users_log';

    public $fillable = ['userId', 'email', 'app', 'accessToken', 'requestUrl', 'requestType', 'statusCode', 'time'];
}
