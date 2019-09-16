<?php
namespace Platform\App\Activity\Models;

use Jenssegers\Mongodb\Model as Eloquent;
/**
* UserNotification
*/
class UserNotification extends Eloquent
{
	protected $collection = 'user_notification';

	protected $connection = 'mongodb_notification';
	
	protected $fillable = [
        'userId', 'userEmail','object'
       ];
}